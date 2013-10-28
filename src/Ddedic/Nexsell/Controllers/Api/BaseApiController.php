<?php namespace Ddedic\Nexsell\Controllers\Api;

use Api, Input, Controller, Request, Response, Nexsell; 

class BaseApiController extends Controller {

	public function __construct()
	{
		$this->beforeFilter('nexsell.api.auth');
	}



	public function missingMethod($parameters)
	{
		return API::createResponse(null, 404);
	}

}