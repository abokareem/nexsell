<?php namespace Ddedic\Nexsell\Commands;

use File;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class InstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'nexsell:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run installation for Nexsell.';



   public function __construct()
    {
        parent::__construct();
    }



	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->comment('Installing Nexsell...');

		// Migrations
		$this->call('migrate');
		$this->call('migrate', array('--package' => 'ddedic/nexsell'));
		$this->call('migrate', array('--bench' => 'ddedic/nexsell'));

		// Seeds
		$this->comment('Seed Nexsell data...');
		$this->call('db:seed', array('--class' => 'Ddedic\\Nexsell\\Seeds\\DatabaseSeeder'));

		// Assets
		// $this->publishAssets();

		// Configuration
		//$this->comment('Publishing configuration...');
		// $this->call('config:publish', array('package' => 'ddedic/nexsell'));
		// $this->call('config:publish', array('--bench' => 'ddedic/nexsell'));


		$this->comment('Done. Nexsell installed.');
	}


}