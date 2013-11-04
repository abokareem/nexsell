<?php namespace Ddedic\Nexsell\Gateways\Providers;


interface GatewayProviderInterface {

	public function sendMessage($from, $to, $text);

	public function getMessages();

	public function getPricing($country);

	public function getDestinationPricing(array $destination);

	public function getAccountBalance();

}