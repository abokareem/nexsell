<?php namespace Ddedic\Nexsell\Apis\Repositories;

use LaravelBook\Ardent\Ardent;
use Ddedic\Nexsell\Apis\ApiInterface;



class ApiEloquentRepo extends Ardent implements ApiInterface {

	protected $table = 'apis';

	public $timestamps = true;
	protected $guarded = array();
	protected $hidden = array('created_at', 'updated_at');

	public static $rules = array(
	    'api_key' => 'required|min:4',
	    'api_secret' => 'required|min:4',
	    'plan_id' => 'required|numeric',
	    'minute_limit' => 'required|numeric|between:1,60',
	    'hour_limit' => 'required|numeric|between:60,1000',
	    'credit_balance' => 'required|numeric',
	    'active' => 'required|numeric'
	);


	public function plan()
	{
		return $this->belongsTo('Ddedic\Nexsell\Plans\Repositories\PlanEloquentRepo', 'plan_id');
	}



	public function messages()
	{
		return $this->hasMany('Ddedic\Nexsell\Messages\Repositories\MessageEloquentRepo', 'api_id');
	}



	// -- Interface methods -- //


	public function getAll()
	{
		return $this->paginate();
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

	public function getId()
	{
		return $this->id;
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

	public function getMessages()
	{
		return $this->messages();
	}

	public function takeCredit($credit)
	{
		if ($credit !== 0 OR $credit !=='')
			$this->credit_balance = (float) $this->credit_balance - (float) $credit;
			$this->save();
	}





















}