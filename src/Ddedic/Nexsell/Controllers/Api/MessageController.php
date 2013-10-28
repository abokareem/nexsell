<?php namespace Ddedic\Nexsell\Controllers\Api;


use Teepluss\Api\Facades\Api;
use Input, Request, Response, Nexsell; 



class MessageController extends BaseApiController {


	protected $client;
	protected $message;
	protected $gateway;


	public function __construct()
	{
		parent::__construct();

		$this->_init_startup();

	}



	public function getIndex()
	{
		// Method not allowed
		return API::createResponse(null, 405);
	}

	public function postIndex()
	{
		// Method not allowed
		return API::createResponse(null, 405);
	}

	public function getSend()
	{
		// Method not allowed		
		return API::createResponse(null, 405);
	}



	// ----------------------------------------------


	private function _init_startup()
	{

		$apiKey = Input::get('api_key');
		$apiSecret = Input::get('api_secret');


		if ($client = Nexsell::authApi($apiKey, $apiSecret))
			$this->client = $client;
		else
			return API::createResponse(null, 401);

	}


	public function postSend()
	{


		//return API::createResponse($this->client->getPlan->getName());
		$o = $this->client->getPlan;

		echo "<pre>";
		var_dump($o);
		//return API::createResponse($o);

		
	}






}