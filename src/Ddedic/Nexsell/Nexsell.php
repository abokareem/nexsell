<?php namespace Ddedic\Nexsell;

use Illuminate\Config\Repository;
use Ddedic\Nexsell\Apis\ApiInterface;
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
use Ddedic\Nexsell\Exceptions\InsufficientCreditsException;
use Ddedic\Nexsell\Exceptions\MessageFailedException;

use App, Response, Numtector;
use Guzzle\Http\Client;



class Nexsell {


	protected $config;

	protected $apis;
	protected $messages;
	protected $gateways;
	protected $plans;
	protected $plan_pricings;

	protected $gatewayProvidersPath;


	public function __construct(Repository $config, ApiInterface $apis, MessageInterface $messages, GatewayInterface $gateways, PlanInterface $plans, PlanPricingInterface $pricings)
	{
		$this->config = $config;

		$this->apis = $apis;
		$this->messages = $messages;
		$this->gateways = $gateways;
		$this->plans = $plans;
		$this->plan_pricings = $pricings;

		$this->gatewayProvidersPath = 'Ddedic\Nexsell\Gateways\Providers\\';
	}



	public static function hello()
	{
		echo 'Nexsell says hello!';
	}


	// ------------------------



	public function authApi($api_key, $api_secret)
	{
		$api = $this->apis->findByApiCredentials($api_key, $api_secret);

		if ( $api !== NULL)
		{
			if ($api->isActive()) return $api;	
		}
		
		return false;
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





	public function sendMessage(ApiInterface $api, $from, $to, $text)
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
 

		$gatewayProvider 	=  $this->_setupGatewayProvider($api);


		if ($pricePerMessage 	=  $this->_getPricePerMessage($api, $gatewayProvider, $destination))
		{

			$numberOfMessages = ceil(strlen($paramText)/160);
			$neededCredit = (float) $numberOfMessages * (float) $pricePerMessage;

			if($api->getCreditBalance() >= $neededCredit)
			{

				$message = new $this->messages();

				$message->gateway_id = $api->plan->gateway->getId();
				$message->country_code = $destination['country']['iso'];
				$message->from = $paramFrom;
				$message->to = $paramTo;
				$message->text = $paramText;
				$message->price = $neededCredit;
				$message->status = 'pending';

				$message = $api->messages()->save($message);


				if ($messageSent = $this->_sendMessage($gatewayProvider, $message))
				{

					// all validations passed, take api's credit, return message sent = true

					$api->takeCredit($neededCredit);
					return TRUE;



				} else {

					throw new MessageFailedException($message->getStatusMessage());
				}


			} else {
				throw new InsufficientCreditsException;
			}

		} else {
			throw new UnsupportedDestinationException;
		}	


	}



	private function _setupGatewayProvider(ApiInterface $api)
	{


 		// Gateway init
 		$gatewayClass = $this->gatewayProvidersPath . $api->plan->gateway->getClassName();

 		if(!class_exists($gatewayClass)){
 			throw new InvalidGatewayProviderException;
 		}

 		if ($api->plan->gateway->isActive() == 0 OR $api->plan->gateway->isActive() == '0')
 		{
 			throw new InactiveGatewayProviderException;
 		}



		return new $gatewayClass ($api->plan->gateway->getApiKey(), $api->plan->gateway->getApiSecret());

	}


	private function _getPricePerMessage(ApiInterface $api, GatewayProviderInterface $gateway, array $destination)
	{
		//dd($api->plan->gePlanId());
		// Plan pricing
		if (! $pricePerMessage = $this->plan_pricings->getMessagePrice($api->getPlan->id, $destination))
		{
			// attempt to get pricing directly from gateway

				// get api pricing
				if($gatewayPrice = $gateway->getDestinationPricing($destination))
				{

					if (! $api->plan->isStrict())
					{

						
						$planPricing = new $this->plan_pricings();

						$planPricing->country_code = $destination['country']['iso'];
						$planPricing->network_code = $destination['network']['network_code'];
						$planPricing->price_original = $gatewayPrice;
						$planPricing->price_adjustment_type = 'percentage';
						$planPricing->price_adjustment_value = $api->plan->getPriceAdjustmentValue();

						$api->plan->pricing()->save($planPricing);
						
						return $pricePerMessage = $this->plan_pricings->getMessagePrice($api->getPlan->id, $destination);

					}


				} else {

					throw new UnsupportedDestinationException;
				}

		} else {
			
			// found message price
			return $pricePerMessage;

		}		
	}


	private function _sendMessage(GatewayProviderInterface $gateway, MessageInterface $message)
	{
		$messageSent = false;

		if ($messageParts = $gateway->sendMessage($message))
		{

			if ($messageParts['status'] == 'success'){

				foreach ($messageParts['message_parts'] as $message_part)
				{
					$message->message_parts()->create($message_part);
				}

				$message->status = 'sent';
				$message->save();
				$messageSent = true;

			} else {

				$message->status = 'failed';
				$message->status_msg = $messageParts['status_msg'];
				$message->save();

			}

		}


		return $messageSent;
		
	}



	public function getApiBalance(ApiInterface $api)
	{
		return $api->getCreditBalance();
	}


}