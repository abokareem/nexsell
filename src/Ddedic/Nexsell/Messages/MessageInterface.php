<?php namespace Ddedic\Nexsell\Messages;



interface MessageInterface {


	public function getMessageId();

	public function getCountryCode();

    public function getSender();

    public function getRecipient();

    public function getText();

    public function getPriceOriginal();

    public function getPrice();

    public function getStatus();

    public function getStatusMessage();

    public function getGateway();

    public function getMessageParts();

    public function isDelivered();
    
    

}