<?php namespace Ddedic\Nexsell\Controllers\Api;

use Input, Controller, Request, Response, Nexsell, API; 

class BaseApiController extends Controller {


	protected $client;


	public function __construct()
	{
		$this->beforeFilter('nexsell.api.auth');


		$this->_init_startup();
	}



	private function _init_startup()
	{

		$apiKey = Input::get('api_key');
		$apiSecret = Input::get('api_secret');


		if ($client = Nexsell::authApi($apiKey, $apiSecret))
			$this->client = $client;
		else
			return API::createResponse(null, 401);

	}


	public function missingMethod($parameters)
	{
		return API::createResponse(null, 404);
	}

}