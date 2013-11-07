<?php


// Set controller path shortcut
$apiControllers = 'Ddedic\Nexsell\Controllers\Api\\';
$frontendControllers = 'Ddedic\Nexsell\Controllers\Frontend\\';
$backendControllers = 'Ddedic\Nexsell\Controllers\Backend\\';


// API ---------------------

Route::group(Config::get('nexsell::routes.api_group_routes'), function() use ($apiControllers)
{

	Route::controller('message', $apiControllers . 'MessageController');
	Route::controller('account', $apiControllers . 'AccountController');

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




// BACKEND ---------------------


Route::group(Config::get('nexsell::routes.backend_group_routes'), function() use ($backendControllers)
{

	Route::post('login', array('uses' => $backendControllers.'AuthController@postLogin', 'as' => 'backend.login'));
	Route::post('logout', array('uses' => $backendControllers.'AuthController@postLogin', 'as' => 'backend.logout'));
	Route::post('password', array('uses' => $backendControllers.'AuthController@postLogin', 'as' => 'backend.password'));

	Route::get('/', array('uses' => $backendControllers.'DashboardController@index'));
	Route::get('dashboard', array('uses' => $backendControllers.'DashboardController@index', 'as' => 'backend.dashboard.index'));

	Route::resource('users', $backendControllers .'UsersController');
	Route::resource('messages', $backendControllers .'MessagesController');
	Route::resource('plans', $backendControllers .'PlansController');
	Route::resource('gateways', $backendControllers .'GatewaysController');

	



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
