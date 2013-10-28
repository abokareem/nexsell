<?php namespace Ddedic\Nexsell\Gateways\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Nexsell\Gateways\GatewayInterface;



class GatewayEloquentRepo extends Eloquent implements GatewayInterface {

	protected $table = 'gateways';

	public $timestamps = true;
	protected $guarded = array();
	protected $hidden = array();




	public function clients()
	{
		return $this->belongsTo('Ddedic\Nexsell\Clients\Repositories\ClientEloquentRepo', 'id');
	}




	// -- Interface methods -- //


	public function getAll()
	{
		return $this->all();
	}


	public function isActive()
	{
		return $this->active;
	}


	public function findById($id)
	{
		return $this->find($id);
	}


	public function getApiKey()
	{
		return $this->api_key;
	}

	public function getApiSecret()
	{
		return $this->api_secret;
	}	

	public function getClients()
	{
		return $this->clients()->all();
	}


}