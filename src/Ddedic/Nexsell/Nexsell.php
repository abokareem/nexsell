<?php namespace Ddedic\Nexsell;

use Illuminate\Config\Repository;
use App;


use Teepluss\Api\Api;


class Nexsell {


	protected $config;


	public function __construct(Repository $config)
	{
		$this->config = $config;
	}






	public static function hello()
	{
		return 'Nexell says hello!';
	}

	public function config()
	{
		return $this->config->get('nexsell::api');
	}

	public function fire()
	{
		return App::make('api')->createResponse('Fakat fire!');
	}

}