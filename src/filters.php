<?php


Route::filter('nexsell.api.auth', function() {


	$apiKey = Input::get('api_key');
	$apiSecret = Input::get('api_secret');

	$authCheck = Nexsell::authApi($apiKey, $apiSecret);

	//dd($authCheck);

	if (! $authCheck)
	{
		return API::createResponse(null, 401);
	}


});