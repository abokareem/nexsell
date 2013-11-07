<?php namespace Ddedic\Nexsell\Controllers\Backend;

use Input, Config, Controller, Request, Response, Nexsell, Api, Theme; 


class DashboardController extends BaseBackendController {



	public function __construct()
	{
		parent::__construct();

	}



	public function index()
	{
		


		return $this->theme->watch('dashboard.index')->render();
	}



}