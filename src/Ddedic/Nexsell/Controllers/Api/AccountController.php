<?php namespace Ddedic\Nexsell\Controllers\Api;


use Nexsell, Api;
use Input, Request, Response; 



class AccountController extends BaseApiController {


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




	public function getBalance()
	{
		if ($accountBalance = Nexsell::getClientBalance($this->client))
		{
			return API::createResponse($accountBalance, 20);
		}
	}








}