<?php namespace Ddedic\Nexsell\Apis;


interface ApiInterface {

	public function getAll();

	public function isActive();

	public function findByApiCredentials($api_key, $api_secret);

	public function findById($id);

	public function getId();

	public function getApiKey();

	public function getApiSecret();

	public function getCreditBalance();

	public function getPlan();

	public function getMessages();

	public function takeCredit($credit);

}