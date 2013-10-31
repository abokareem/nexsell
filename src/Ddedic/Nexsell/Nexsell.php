<?php namespace Ddedic\Nexsell;

use Illuminate\Config\Repository;
use Ddedic\Nexsell\Clients\ClientInterface;
use Ddedic\Nexsell\Messages\MessageInterface;
use Ddedic\Nexsell\Gateways\GatewayInterface;
use Ddedic\Nexsell\Countries\CountryInterface;
use Ddedic\Nexsell\Plans\PlanInterface;
use Ddedic\Nexsell\Plans\PlanPricingInterface;

use Ddedic\Nexsell\Exceptions\InvalidFromFieldException;
use Ddedic\Nexsell\Exceptions\InvalidToFieldException;
use Ddedic\Nexsell\Exceptions\InvalidDestinationException;

class Nexsell {


	protected $config;

	protected $clients;
	protected $messages;
	protected $gateways;
	protected $plans;
	protected $plan_pricings;
	protected $countries;


	public function __construct(Repository $config, ClientInterface $clients, MessageInterface $messages, GatewayInterface $gateways, PlanInterface $plans, PlanPricingInterface $pricings, CountryInterface $countries)
	{
		$this->config = $config;

		$this->clients = $clients;
		$this->messages = $messages;
		$this->gateways = $gateways;
		$this->plans = $plans;
		$this->plan_pricings = $pricings;
		$this->countries = $countries;
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



		// Detect possible countries
		$possibleCountries = $this->countries->detectCountriesByPhoneNumber($paramTo);

		if (count($possibleCountries) == 0)
			throw new InvalidDestinationException;



		// Plan pricing
		if ($pricePerMessage = $this->plan_pricings->getPricingForDestination($client->getPlan->id, $possibleCountries, $paramTo))
		{

			//echo $pricePerMessage;

			// prebroji poruke, pomnozi sa cijenom, usporedi sa balansom, pokusaj poslati





		} else {

			//echo 'nema';

			// pokusaj dobiti Live pricing od gatewaya, spremi u bazu odgovor, pa ovo gore, prebroji, mnozi, usporedi i pokusaj poslati
		}




		//dd($pricePerMessage);

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