<?php namespace Ddedic\Nexsell;

use Guzzle\Http\Client;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;



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
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	
	public function register()
	{

		// Numtector
        $this->app->register('Ddedic\\Numtector\\NumtectorServiceProvider');
        
        // Api
		$this->app['api'] = $this->app->share(function($app)
		{
			$remoteClient = new Client();
			return new Api($app['config'], $app['router'], $app['request'], $remoteClient);
		});




		$this->app->singleton('Ddedic\Nexsell\Apis\ApiInterface', 'Ddedic\Nexsell\Apis\Repositories\ApiEloquentRepo');
		$this->app->singleton('Ddedic\Nexsell\Messages\MessageInterface', 'Ddedic\Nexsell\Messages\Repositories\MessageEloquentRepo');
		$this->app->singleton('Ddedic\Nexsell\Gateways\GatewayInterface', 'Ddedic\Nexsell\Gateways\Repositories\GatewayEloquentRepo');
		$this->app->singleton('Ddedic\Nexsell\Plans\PlanInterface', 'Ddedic\Nexsell\Plans\Repositories\PlanEloquentRepo');
		$this->app->singleton('Ddedic\Nexsell\Plans\PlanPricingInterface', 'Ddedic\Nexsell\Plans\Repositories\PlanPricingEloquentRepo');



		$this->app->bind('nexsell', function($app)
		{
			return new Nexsell($app['config'],
								 new Apis\Repositories\ApiEloquentRepo,
								 new Messages\Repositories\MessageEloquentRepo,
								 new Gateways\Repositories\GatewayEloquentRepo,
								 new Plans\Repositories\PlanEloquentRepo,
								 new Plans\Repositories\PlanPricingEloquentRepo
							 );
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
		return array('nexsell');
	}

}