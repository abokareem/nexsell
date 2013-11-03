<?php namespace Ddedic\Nexsell\Plans\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Nexsell\Plans\PlanPricingInterface;



class PlanPricingEloquentRepo extends Eloquent implements PlanPricingInterface {

	protected $table = 'plan_pricing';
	public $timestamps = true;
	protected $fillable = array();
	protected $visible = array();
	protected $hidden = array('created_at', 'updated_at', 'id');



	public function plan()
	{
		return $this->belongsTo('Ddedic\Nexsell\Plans\Repositories\PlanEloquentRepo', 'id');
	}




	public function getAll()
	{
		return $this->all();
	}

	public function getCountryCode()
	{
		return $this->country_code;
	}

	public function getNetworkCode()
	{
		return $this->network_code;
	}

	public function getNetworkName()
	{
		return $this->network_name;
	}	

	public function getPriceOriginal()
	{
		return $this->price_original;
	}

	public function getNumberPrefix()
	{
		return $this->number_prefix;
	}

	public function getPriceAdjustmentType()
	{
		return $this->price_adjustment_type;
	}

	public function getPriceAdjustmentValue()
	{
		return $this->price_adjustment_value;
	}

	
	public function findById($id)
	{
		return $this->find($id);
	}


	public function getMessagePrice($planId, $destination)
	{

    	$found = false;
    	$priceValue = null;


    	
        $check = $this->where('plan_id', $planId)->where('country_code', $destination['country']['iso'])->where('network_code', $destination['network']['network_code'])->first();
        if( $check ) {

        		if ($check->price_adjustment_type == "percentage")
        			$priceValue = $check->price_original + ($check->price_original * ($check->price_adjustment_value / 100));
        		else
        			$priceValue = $check->price_adjustment_value;

        }
	            	


    	return $priceValue;		

	}




































}