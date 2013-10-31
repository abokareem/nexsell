<?php namespace Ddedic\Nexsell\Countries;


interface CountryInterface {

	public function getAll();

	public function detectCountriesByPhoneNumber($phoneNumber);

}