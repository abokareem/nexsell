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
		return $this->belongsTo('Ddedic\Nexsell\Clients\Repositories\ClientEloquentRepo', 'id');
	}

	public function pricing()
	{
		//return $this->hasMany('Pricing', 'plan_id');
		return;
	}


	public function getAll()
	{
		return $this-all();
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

}