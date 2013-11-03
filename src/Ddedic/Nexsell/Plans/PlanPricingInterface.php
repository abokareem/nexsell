<?php namespace Ddedic\Nexsell\Plans;


interface PlanPricingInterface {

	public function getAll();

	public function getCountryCode();

	public function getNetworkName();

	public function getNetworkCode();

	public function getNumberPrefix();

	public function getPriceOriginal();

	public function getPriceAdjustmentType();

	public function getPriceAdjustmentValue();

	

	public function findById($id);

	public function getMessagePrice($planId, $destination);

}