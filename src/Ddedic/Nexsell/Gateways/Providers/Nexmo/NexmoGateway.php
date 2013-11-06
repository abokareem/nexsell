<?php namespace Ddedic\Nexsell\Gateways\Providers\Nexmo;

use Ddedic\Nexsell\Messages\MessageInterface;
use Ddedic\Nexsell\Gateways\Providers\GatewayProviderInterface;
use Guzzle\Http\Client;
use Response, Str;

use Ddedic\Nexsell\Exceptions\InvalidGatewayResponseException;
use Ddedic\Nexsell\Exceptions\InvalidRequestException;



class NexmoGateway implements GatewayProviderInterface
{

	protected $api_key;
	protected $api_secret;
	protected $api_endpoint = 'https://rest.nexmo.com';

	protected $remoteClient;

	public static $send_message_url = 'sms/json';
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


	public function sampleResponse(MessageInterface $message)
	{
		$response = array(
				'message-count'	=> 2,
				'messages'		=> array( 
										array(
											'status' => '0',
											'message-id' => Str::quickRandom(),
											'to'	=> $message->to,
											'client-ref' => $message->id,
											'remaining-balance'	=> 10.0000,
											'message-price' => 0.0110000,
											'network' => '2233ED'
										),
										array(
											'status' => '3',
											'message-id' => Str::quickRandom(),
											'to'	=> $message->to,
											'client-ref' => $message->id,
											'remaining-balance'	=> 10.0000,
											'message-price' => 0.0110000,
											'network' => '2233ED',
											'error-text' => 'Dummy error msg!'
										)
									)				
			);

		return $response;

	}


	public function sendMessage(MessageInterface $message)
	{
		$response = $this->postRemote(self::$send_message_url, array('from' => $message->from, 'to' =>  $message->to, 'text' => $message->text, 'client-ref' => $message->id));
		//$response = $this->sampleResponse($message);
		return $this->formNexmoMessageResponse($response);
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






	private function formNexmoMessageResponse($nexmoResponse)
	{
		$response = array('status' => 'error', 'status_msg' => null);

		if (isset($nexmoResponse['message-count']) AND (int)$nexmoResponse['message-count'] > 0)
		{

			if (count($nexmoResponse['messages']) == $nexmoResponse['message-count'])
			{

				$allSuccess = true;
				$errorMessage = null;

				foreach ($nexmoResponse['messages'] as $message)
				{
					if ($message['status'] !== '0') {

						$allSuccess = false;
						$errorMessage = $message['error-text'];
						break;						

					}

				}

				if ($allSuccess)
				{

					$buildMessageParts = array();
					$totalPrice = 0;


					foreach ($nexmoResponse['messages'] as $message)
					{

						$buildMessageParts[] = array(
								'id'			=>	$message['message-id'],
								'message_id'	=>	isset($message['client-ref']) ? $message['client-ref'] : 0,
								'network'		=>	$message['network'],
								'to'			=>	$message['to'],
								'price'			=>	$message['message-price']
							);

						$totalPrice = (float) $totalPrice + (float) $message['message-price'];

					}

					$response = array('status' => 'success', 'message_parts' => $buildMessageParts, 'total_price' => $totalPrice);

				} else {

					$response = array('status' => 'error', 'status_msg' => $errorMessage);
				}


			}

			
		} else {

			$response = array('status' => 'error', 'status_msg' => 'Empty response from Nexmo gateway');

		}


		return $response;
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


        return $this->parseResponse($response);
	}


	private function postRemote($uri, $params)
	{
        $base_params = array('api_key' => $this->api_key, 'api_secret' => $this->api_secret);
        $parameters = ($params) ? array_merge($base_params, $params) : $base_params;

		$request = $this->remoteClient->post($uri, array(), $parameters, array());

        
       try {
            
            $response = $request->send();    

            

        } catch (\Guzzle\Common\Exception\GuzzleException $e) {
            
            throw new InvalidRequestException('InvalidRequestException');

        }




        return $this->parseResponse($response);
	}


	private function parseResponse($response)
	{


        // Body responsed.
        $body = (string) $response->getBody();

        if (($response->getStatusCode() == 200) AND (strpos($response->getContentType(), 'application/json') !== FALSE))
        {

            if (function_exists('json_decode') and is_string($body))
            {
                //$body = $this->normaliseKeys(json_decode($body, true));
                $body = json_decode($body, true);
            }

        } else {

        	throw new InvalidGatewayResponseException;

        }		

        return $body;

	}


	private function normaliseKeys ($obj) {
		// Determine is working with a class or araay
		if ($obj instanceof stdClass) {
			$new_obj = new stdClass();
			$is_obj = true;
		} else {
			$new_obj = array();
			$is_obj = false;
		}


		foreach($obj as $key => $val){
			// If we come across another class/array, normalise it
			if ($val instanceof stdClass || is_array($val)) {
				$val = $this->normaliseKeys($val);
			}
			
			// Replace any unwanted characters in they key name
			if ($is_obj) {
				$new_obj->{str_replace('-', '', $key)} = $val;
			} else {
				$new_obj[str_replace('-', '', $key)] = $val;
			}
		}

		return $new_obj;
	}


}