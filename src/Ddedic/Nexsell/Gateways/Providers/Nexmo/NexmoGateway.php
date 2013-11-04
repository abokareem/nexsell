<?php namespace Ddedic\Nexsell\Gateways\Providers\Nexmo;

use Ddedic\Nexsell\Gateways\Providers\GatewayProviderInterface;
use Guzzle\Http\Client;
use Response;

use Ddedic\Nexsell\Exceptions\InvalidGatewayResponseException;
use Ddedic\Nexsell\Exceptions\InvalidRequestException;



class NexmoGateway implements GatewayProviderInterface
{

	protected $api_key;
	protected $api_secret;
	protected $api_endpoint = 'https://rest.nexmo.com';

	protected $remoteClient;

    public static $balance_url 	= 'account/get-balance';
    public static $pricing_url 	= 'account/get-pricing/outbound';
    public static $account_url 	= 'account/settings';
    public static $number_url 	= 'account/numbers';
    public static $top_up_url 	= 'account/top-up';
    public static $search_url 	= 'number/search';
    public static $buy_url 		= 'number/buy';
    public static $cancel_url 	= 'number/cancel';
    public static $update_url 	= 'number/update';
    public static $message_url 	= 'search/message';
    public static $messages_url = 'search/messages';
    public static $rejections_url = 'search/rejections';	



	public function __construct($api_key, $api_secret)
	{

		if($api_key === NULL OR $api_secret === NULL)
			throw new RequiredFieldsException;


		$this->api_key = $api_key;
		$this->api_secret = $api_secret;


		$this->remoteClient = new Client($this->api_endpoint);

	}



	public function sendMessage($from, $to, $text)
	{
		return;
	}



	public function getMessages()
	{
		return;
	}



	public function getPricing($country)
	{
		return $this->getRemote(self::$pricing_url, array('country' => $country));
	}


	public function getDestinationPricing(array $destination)
	{

		$found = false;
		$price = null;
		$response = $this->getPricing($destination['country']['iso']);

			if(isset($response))
			{

				if(isset($response['country']))
				{
					if(isset($response['networks']))
					{
						foreach($response['networks'] as $network)
						{


							if ($network['code'] == $destination['network']['network_code'])
							{
								$found = true;
								$price = $network['mtPrice'];
							}

							if($found) { break; }
						}
					}

				}

			}


			return $price;
	}


	public function getAccountBalance()
	{
		return $this->getRemote(self::$balance_url, array());
	}













	private function getRemote($uri, $params)
	{
        $base_params = array('api_key' => $this->api_key, 'api_secret' => $this->api_secret);
        $parameters = ($params) ? array_merge($base_params, $params) : $base_params;

		$request = $this->remoteClient->get($uri, array(), array(
					    'query' => $parameters
					));

        
       try {
            
            $response = $request->send();    

            

        } catch (\Guzzle\Common\Exception\GuzzleException $e) {
            
            /*
            $response = array(
                'code'       => $e->getResponse()->getStatusCode(),
                'message'    => $e->getResponse()->getReasonPhrase()
            );
            */
            //throw new InvalidRequestException('InvalidRequestException: Code (' . $response['code'] . ') - Message: ' . $response['message']);

            throw new InvalidRequestException('InvalidRequestException');

        }


        // Body responsed.
        $body = (string) $response->getBody();

        // Decode json content.
        if ($response->getContentType() == 'application/json' OR ($response->getContentType() == 'application/json;charset=UTF-8'))
        {
            if (function_exists('json_decode') and is_string($body))
            {
                $body = json_decode($body, true);
            }

        } else {

        	throw new InvalidGatewayResponseException();

        }


        return $body;
	}





	private function postRemote()
	{

	}











}