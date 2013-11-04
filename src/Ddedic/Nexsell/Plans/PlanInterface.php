<?php namespace Ddedic\Nexsell\Plans;


interface PlanInterface {

	public function getAll();

	public function getName();

	public function gePlanId();

	public function getDescription();

	public function getPriceAdjustment();

	public function isStrict();

	public function getPriceAdjustmentValue();

	public function findById($id);

	public function getPricing();
}