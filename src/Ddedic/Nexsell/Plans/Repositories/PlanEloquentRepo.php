<?php namespace Ddedic\Nexsell\Plans\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;

use Ddedic\Nexsell\Plans\PlanInterface;

class PlanEloquentRepo extends Eloquent implements PlanInterface {

	protected $table = 'plans';
	public $timestamps = true;
	protected $softDelete = true;
	protected $fillable = array('name', 'description');
	protected $visible = array();

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
		return $this->getName();
	}

}