<?php


// Set controller path shortcut
$apiControllers = 'Ddedic\Nexsell\Controllers\Api\\';
$frontendControllers = 'Ddedic\Nexsell\Controllers\Frontend\\';
$backendControllers = 'Ddedic\Nexsell\Controllers\Backend\\';


// API ---------------------

Route::group(Config::get('nexsell::routes.api_group_routes'), function() use ($apiControllers)
{

	Route::controller('message', $apiControllers . 'MessageController');
	//Route::controller('account', $apiControllers . 'AccountController');

});








// FRONT ---------------------


Route::group(Config::get('nexsell::routes.frontend_group_routes'), function() use ($frontendControllers)
{

	Route::post('login', array('uses' => $frontendControllers.'AuthController@postLogin', 'as' => 'frontend.login'));

	Route::get('test', function()
	{

		//test method

	});


});









/*
App::error(function(Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $exception, $code)
{
	 //if (Request::isJson() || Request::ajax()) return API::createResponse(null, $code);
	 //return API::createResponse(null, $code);
	 return 'Error '.$code.' : '.$exception->getMessage() ."\n";
});
*/




// Handles 404 - Not found API json reponse
App::missing(function($exception)
{
	return Response::view('nexsell::errors.404', array('error' => $exception), 404);
});
