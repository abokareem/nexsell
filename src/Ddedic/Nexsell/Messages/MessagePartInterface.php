<?php namespace Ddedic\Nexsell\Messages;



interface MessagePartInterface {


	public function getPartId();

	public function getNetwork();

    public function getRecipient();

    public function getPrice();

    public function getStatus();

    public function getDeliveryReport();
    

}