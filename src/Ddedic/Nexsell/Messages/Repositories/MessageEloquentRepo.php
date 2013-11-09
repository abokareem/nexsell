<?php namespace Ddedic\Nexsell\Messages\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Nexsell\Messages\MessageInterface;


class MessageEloquentRepo extends Eloquent implements MessageInterface {

    protected $table = 'messages';

    public $timestamps = true;
    protected $guarded = array();
    protected $hidden = array('created_at', 'updated_at', 'api_id', 'gateway_id');


    public function api()
    {
        return $this->belongsTo('Ddedic\Nexsell\Apis\Repositories\ApiEloquentRepo', 'id');
    }

    public function gateway()
    {
        return $this->hasOne('Ddedic\Nexsell\Gateways\Repositories\GatewayEloquentRepo', 'id');
    }    

    public function message_parts()
    {
        return $this->hasMany('Ddedic\Nexsell\Messages\Repositories\MessagePartEloquentRepo', 'message_id');
    }    




    public function getMessageId()
    {
        return $this->id;
    }

    public function getCountryCode()
    {
        return $this->country_code;
    }    

    public function getSender()
    {
        return $this->from;
    }

    public function getRecipient() {
        return $this->to;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getPriceOriginal()
    {
        return $this->price_original;
    }

    public function getPrice()
    {
        return $this->price;
    }    

    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusMessage()
    {
        return $this->status_msg;
    }

    public function getGateway()
    {
        return $this->gateway();
    }

    public function getMessageParts()
    {
        return $this->message_parts();
    }

    public function isDelivered()
    {
        $delivered = FALSE;
        $message_parts = $this->getMessageParts;
        $success_count = 0;

        foreach ($message_parts as $msgpart)
        {
            if ($msgpart->getDeliveryReport)
                if($msgpart->getDeliveryReport->getStatus() == 'delivered')
                    $success_count++;
        }

        if ($success_count == count($message_parts))
            $delivered = TRUE;


        return $delivered;
    }








}