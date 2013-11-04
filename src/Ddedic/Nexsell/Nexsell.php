<?php namespace Ddedic\Nexsell;

use Illuminate\Config\Repository;
use Ddedic\Nexsell\Clients\ClientInterface;
use Ddedic\Nexsell\Messages\MessageInterface;
use Ddedic\Nexsell\Gateways\GatewayInterface;
use Ddedic\Nexsell\Gateways\Providers\GatewayProviderInterface;
use Ddedic\Nexsell\Plans\PlanInterface;
use Ddedic\Nexsell\Plans\PlanPricingInterface;

use Ddedic\Nexsell\Exceptions\InvalidFromFieldException;
use Ddedic\Nexsell\Exceptions\InvalidToFieldException;
use Ddedic\Nexsell\Exceptions\InvalidDestinationException;
use Ddedic\Nexsell\Exceptions\InvalidGatewayProviderException;
use Ddedic\Nexsell\Exceptions\InactiveGatewayProviderException;
use Ddedic\Nexsell\Exceptions\UnsupportedDestinationException;
use Ddedic\Nexsell\Exceptions\InvalidRequestException;

use App, Response, Numtector;
use Guzzle\Http\Client;



class Nexsell {


	protected $config;

	protected $clients;
	protected $messages;
	protected $gateways;
	protected $plans;
	protected $plan_pricings;

	protected $gatewayProvidersPath;


	public function __construct(Repository $config, ClientInterface $clients, MessageInterface $messages, GatewayInterface $gateways, PlanInterface $plans, PlanPricingInterface $pricings)
	{
		$this->config = $config;

		$this->clients = $clients;
		$this->messages = $messages;
		$this->gateways = $gateways;
		$this->plans = $plans;
		$this->plan_pricings = $pricings;

		$this->gatewayProvidersPath = 'Ddedic\Nexsell\Gateways\Providers\\';
	}



	public static function hello()
	{
		echo 'Nexell says hello!';
	}



	public function testClient()
	{

        $remoteClient = new Client('https://rest.nexmo.com');

        // Parameters for GET, POST
        //$parameters = ($parameters) ? current($parameters) : array();


        $method = 'get';
        $uri = 'account/get-pricing/outbound';
        $parameters = array('api_key' => '6d3970a2', 'api_secret' => '4424dd3d', 'country' => 'BA');

        // Make request.
        // $request = $remoteClient->get($uri, array(), $parameters);
		$request = $remoteClient->get($uri, array(), array(
					    'query' => $parameters
					));


        // Send request.
        
       // try {
            
			


            $response = $request->send();    

       /* } catch (\Guzzle\Common\Exception\GuzzleException $e) {
            
            //dd($e->getResponse()->getReasonPhrase());
            $response = array(
                'status'     => 'error',
                'code'       => $e->getResponse()->getStatusCode(),
                'message'    => $e->getResponse()->getReasonPhrase()
            );

            return Response::json($response);            
        }
	*/



        // Body responsed.
        $body = (string) $response->getBody();


        // Decode json content.
        if ($response->getContentType() == 'application/json' OR ($response->getContentType() == 'application/json;charset=UTF-8'))
        {
            if (function_exists('json_decode') and is_string($body))
            {
                $body = json_decode($body, true);
            }
        }


        return $body;


	}




	// ------------------------



	public function authApi($api_key, $api_secret)
	{
		$client = $this->clients->findByApiCredentials($api_key, $api_secret);

		if ( $client !== NULL)
		{
			if ($client->isActive()) return $client;	
		}
		
		return false;
	}



	public function sendMessage(ClientInterface $client, $from, $to, $text)
	{


		// Params Validation

		if($from === NULL OR $to === NULL OR $text === NULL)
			throw new RequiredFieldsException;
		
		$paramFrom = $this->validateOriginatorFormat($from);
		$paramTo = $this->validateDestinationFormat($to);
		$paramText = iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);


		if($paramFrom == '')
			throw new InvalidFromFieldException;

		if($paramTo == '')
			throw new InvalidToFieldException;



		// process destination number
		if(! $destination = Numtector::processNumber($paramTo))
			throw new InvalidDestinationException;
 


		$gatewayProvider 	=  $this->_setupGatewayProvider($client);
		$pricePerMessage 	=  $this->_getPricePerMessage($client, $gatewayProvider, $destination);




	
		dd ($pricePerMessage);


	}



	private function _setupGatewayProvider(ClientInterface $client)
	{

 		// Gateway init
 		$gatewayClass = $this->gatewayProvidersPath . $client->getGateway->class_name;

 		if(!class_exists($gatewayClass)){
 			throw new InvalidGatewayProviderException;
 		}

 		if ($client->getGateway->active == 0 OR $client->getGateway->active == '0')
 		{
 			throw new InactiveGatewayProviderException;
 		}

		return new $gatewayClass ($client->getGateway->api_key, $client->getGateway->api_secret);




	}


	private function _getPricePerMessage(ClientInterface $client, GatewayProviderInterface $gateway, array $destination)
	{
		//dd($client->plan->gePlanId());
		// Plan pricing
		if (! $pricePerMessage = $this->plan_pricings->getMessagePrice($client->getPlan->id, $destination))
		{
			// attempt to get pricing directly from gateway

				// get api pricing
				if($gatewayPrice = $gateway->getDestinationPricing($destination))
				{

					if (! $client->plan->isStrict())
					{

						
						$planPricing = new $this->plan_pricings();

						$planPricing->country_code = $destination['country']['iso'];
						$planPricing->network_code = $destination['network']['network_code'];
						$planPricing->price_original = $gatewayPrice;
						$planPricing->price_adjustment_type = 'percentage';
						$planPricing->price_adjustment_value = $client->plan->getPriceAdjustmentValue();

						$client->plan->pricing()->save($planPricing);
						
						
						return $pricePerMessage = $this->plan_pricings->getMessagePrice($client->getPlan->id, $destination);

					}


				} else {

					throw new UnsupportedDestinationException;
				}

		} else {
			
			// found message price, attempt to send message
			return $pricePerMessage;

		}		
	}


    private function validateOriginatorFormat($inp){
            // Remove any invalid characters
            $ret = preg_replace('/[^a-zA-Z0-9]/', '', (string)$inp);

            if(preg_match('/[a-zA-Z]/', $inp)){

                    // Alphanumeric format so make sure it's < 11 chars
                    $ret = substr($ret, 0, 11);

            } else {

                    // Numerical, remove any prepending '00'
                    if(substr($ret, 0, 2) == '00'){
                            $ret = substr($ret, 2);
                            $ret = substr($ret, 0, 15);
                    }

                    // Numerical, remove any prepending '+'
                    if(substr($ret, 0, 1) == '+'){
                            $ret = substr($ret, 1);
                            $ret = substr($ret, 0, 15);
                    }
            }
            
            return (string)$ret;
    }

    private function validateDestinationFormat($inp){
            // Remove any invalid characters
            $ret = preg_replace('/[^0-9]/', '', (string)$inp);

            // Numerical, remove any prepending '00'
            if(substr($ret, 0, 2) == '00'){
                    $ret = substr($ret, 2);
                    $ret = substr($ret, 0, 15);
            }

            // Numerical, remove any prepending '+'
            if(substr($ret, 0, 1) == '+'){
                    $ret = substr($ret, 1);
                    $ret = substr($ret, 0, 15);
            }            
            
            return (string)$ret;
    }

}