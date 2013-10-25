<?php namespace Ddedic\Nexsell;

use Guzzle\Http\Client;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

use Ddedic\Nexsell\Clients;
use Ddedic\Nexsell\Clients\Repositories\ClientEloquentRepo as ClientProvider;



use Teepluss\Api\Api;

class NexsellServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ddedic/nexsell', 'nexsell');


		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
		$loader->alias('Nexsell', 'Ddedic\Nexsell\Facades\NexsellFacade');

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['api'] = $this->app->share(function($app)
		{
			$remoteClient = new Client();

			return new Api($app['config'], $app['router'], $app['request'], $remoteClient);
		});


		$this->app['nexsell'] = $this->app->share(function($app)
		{
			//return $this->app->make('api')->createResponse($app['config']['nexsell::api']);
		  	return new Nexsell($app['config'], new ClientProvider);
		});



	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('nexsell');
	}

}