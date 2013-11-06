<?php namespace Ddedic\Nexsell\Gateways\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Nexsell\Gateways\GatewayInterface;



class GatewayEloquentRepo extends Eloquent implements GatewayInterface {

	protected $table = 'gateways';

	public $timestamps = true;
	protected $guarded = array();
	protected $hidden = array();




	public function plans()
	{
		return $this->hasMany('Ddedic\Nexsell\Plans\Repositories\PlanEloquentRepo', 'gateway_id');
	}

	public function message()
	{
		return $this->belongsTo('Ddedic\Nexsell\Messages\Repositories\MessageEloquentRepo', 'gateway_id');
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

	public function getId()
	{
		return $this->id;
	}

	public function getClassName()
	{
		return $this->class_name;
	}	

	public function getApiKey()
	{
		return $this->api_key;
	}

	public function getApiSecret()
	{
		return $this->api_secret;
	}	

	public function getPlans()
	{
		return $this->plans()->all();
	}


}