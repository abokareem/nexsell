<?php namespace Ddedic\Nexsell\Controllers\Backend;

use Api, Input, Config, Controller, Request, Response, Nexsell, Theme; 

class BaseBackendController extends Controller {


	protected $theme;


	public function __construct()
	{
		//$this->beforeFilter('nexsell.backend.auth');
		$this->_setupTheme();
	}



	private function _setupTheme()
	{
		// Setup Theme
		$backendTheme = Config::get('nexsell::config.backendTheme');

		if (Theme::exists($backendTheme))
			$this->theme = Theme::uses($backendTheme);
		else
			$this->theme = Theme::uses('default');
		
	}














	public function missingMethod($parameters)
	{
		//
	}

}