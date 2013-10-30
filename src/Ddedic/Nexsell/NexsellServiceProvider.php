<?php namespace Ddedic\Nexsell;

use Guzzle\Http\Client;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

use Ddedic\Nexsell\Clients;
use Ddedic\Nexsell\Clients\Repositories\ClientEloquentRepo as ClientProvider;
use Ddedic\Nexsell\Messages\Repositories\MessageEloquentRepo as MessageProvider;
use Ddedic\Nexsell\Gateways\Repositories\GatewayEloquentRepo as GatewayProvider;




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
		
		$loader = AliasLoader::getInstance();


		// Nexsell
		$loader->alias('Nexsell', 'Ddedic\Nexsell\Facades\NexsellFacade');
		$this->package('ddedic/nexsell', 'nexsell');

		// API
		$loader->alias('API', 'Ddedic\Nexsell\Facades\ApiFacade');



		$this->bootCommands();




        // Inclusions
        require __DIR__.'/../../filters.php';
        require __DIR__.'/../../routes.php';
		

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
		  	return new Nexsell($app['config'], new ClientProvider, new MessageProvider, new GatewayProvider);
		});


	}





   public function bootCommands()
    {
        // Add install command to IoC
        $this->app['nexsell.commands.install'] = $this->app->share(function($app) {
                return new Commands\InstallCommand;
        });

        
        // Add refresh command to IoC
        $this->app['nexsell.commands.reinstall'] = $this->app->share(function($app) {
                return new Commands\ReinstallCommand;
        });


        // Now register all the commands
        $this->commands('nexsell.commands.install', 'nexsell.commands.reinstall');
    }




	

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('nexsell', 'nexsell.api');
	}

}