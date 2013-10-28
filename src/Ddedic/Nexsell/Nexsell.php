<?php namespace Ddedic\Nexsell;

use Illuminate\Config\Repository;
use Ddedic\Nexsell\Clients\ClientInterface;

use API;


class Nexsell {


	protected $config;

	protected $clients;


	public function __construct(Repository $config, ClientInterface $clients)
	{
		//dd($client);

		$this->config = $config;
		$this->clients = $clients;
	}






	public static function hello()
	{
		echo 'Nexell says hello!';
	}

	public function config()
	{
		return $this->config->get('nexsell::api');
	}

	public function fire($client_id)
	{
		//return X::createResponse('Fakat fire!');

		$client = $this->clients->findById($client_id);

		if($client)
		{
			$output = $client->getPlan()->get();

		} else {

			$output = array('error' => 'Client not found');
		}


		
		return API::createResponse($output);
		//return API::createResponse($this->clients->getAll())
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



	public function sendMessage(ClientInterface $client, MessageInterface $message, GatewayInterface $gateway)
	{
		
		$message = new Message('sender-name', 'receiver-phone-number', 'message text');

		$gateway = new NexmoGateway('username', 'password');
		$gateway->send($message);

		echo 'Account Balance is: ', $gateway->getBalance();
		

	}






}