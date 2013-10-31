<?php namespace Ddedic\Nexsell\Plans\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Nexsell\Plans\PlanInterface;



class PlanEloquentRepo extends Eloquent implements PlanInterface {

	protected $table = 'plans';
	public $timestamps = true;
	protected $fillable = array();
	protected $visible = array();



	public function client()
	{
		return $this->belongsToMany('Ddedic\Nexsell\Clients\Repositories\ClientEloquentRepo', 'id');
	}

	public function pricing()
	{
		return $this->hasMany('Ddedic\Nexsell\Plans\Repositories\PlanPricingEloquentRepo', 'plan_id');
	}



	public function getAll()
	{
		return $this->all();
	}

	public function gePlanId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getDescription()
	{
		return $this->description;
	}	

	public function getPriceAdjustment()
	{
		return $this->price_adjustment;
	}

	public function isStrict()
	{
		if ($this->strict == '1')
			return true;
		else
			return false;
	}

	public function findById($id)
	{
		return $this->find($id);
	}

	public function getPricing()
	{
		return $this->pricing();
	}

}