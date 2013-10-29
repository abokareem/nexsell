<?php namespace Ddedic\Nexsell\Clients\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Nexsell\Clients\ClientInterface;



class ClientEloquentRepo extends Eloquent implements ClientInterface {

	protected $table = 'clients';

	public $timestamps = true;
	protected $guarded = array('api_key', 'api_secret', 'credit_balance');
	protected $hidden = array();




	public function plan()
	{
		return $this->hasOne('Ddedic\Nexsell\Plans\Repositories\PlanEloquentRepo', 'id');
	}

	public function gateway()
	{
		return $this->hasOne('Ddedic\Nexsell\Gateways\Repositories\GatewayEloquentRepo', 'id');
	}

	public function messages()
	{
		return $this->hasMany('Ddedic\Nexsell\Messages\Repositories\MessageEloquentRepo', 'client_id');
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

	public function findByApiCredentials($api_key, $api_secret)
	{
        return $this->where('api_key', $api_key)->where('api_secret', $api_secret)->first();
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

	public function getCreditBalance()
	{
		return $this->credit_balance;
	}

	public function getPlan()
	{
		return $this->plan();
	}

	public function getGateway()
	{
		return $this->gateway();
	}

	public function getMessages()
	{
		return $this->messages();
	}


}