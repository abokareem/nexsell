<?php namespace Ddedic\Nexsell\Messages;



interface MessageInterface {


    public function getSender();

    public function getPhoneNumber();

    public function getText();
    

}