<?php namespace Ddedic\Nexsell;

use Illuminate\Config\Repository;
use Ddedic\Nexsell\Clients\ClientInterface;

use Teepluss\Api\Facades\Api;



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
		return 'Nexell says hello!';
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
	}


	public function nexsell_send_msg()
	{

		
		
	}





}