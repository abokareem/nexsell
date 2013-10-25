<?php namespace Ddedic\Nexsell\Clients;


interface ClientInterface {

	public function getAll();


	public function findById($id);

	public function getApiKey();

	public function getApiSecret();

	public function getCreditBalance();

	public function getPlan();







}