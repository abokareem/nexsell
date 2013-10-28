<?php namespace Ddedic\Nexsell\Plans;


interface PlanInterface {

	public function getAll();

	public function getName();

	public function getDescription();

	public function getPriceAdjustment();

	public function isStrict();

	public function findById($id);


}