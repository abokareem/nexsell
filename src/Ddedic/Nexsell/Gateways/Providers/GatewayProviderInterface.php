<?php namespace Ddedic\Nexsell\Gateways\Providers;

use Ddedic\Nexsell\Messages\MessageInterface;


interface GatewayProviderInterface {

	public function sendMessage(MessageInterface $message);

	public function getMessages();

	public function getPricing($country);

	public function getDestinationPricing(array $destination);

	public function getAccountBalance();

}