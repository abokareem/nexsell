<?php namespace Ddedic\Nexsell;

use Illuminate\Config\Repository;
use Ddedic\Nexsell\Clients\ClientInterface;
use Ddedic\Nexsell\Messages\MessageInterface;
use Ddedic\Nexsell\Gateways\GatewayInterface;

use Ddedic\Nexsell\Exceptions\InvalidFromFieldException;
use Ddedic\Nexsell\Exceptions\InvalidToFieldException;


class Nexsell {


	protected $config;

	protected $clients;
	protected $messages;
	protected $gateways;


	public function __construct(Repository $config, ClientInterface $clients, MessageInterface $messages, GatewayInterface $gateways)
	{
		$this->config = $config;

		$this->clients = $clients;
		$this->messages = $messages;
		$this->gateways = $gateways;
	}



	public static function hello()
	{
		echo 'Nexell says hello!';
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



		
		// Nesto





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
            
            return (string)$ret;
    }

}