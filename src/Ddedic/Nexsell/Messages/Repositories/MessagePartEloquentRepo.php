<?php namespace Ddedic\Nexsell\Messages\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Nexsell\Messages\MessagePartInterface;


class MessagePartEloquentRepo extends Eloquent implements MessagePartInterface {

    protected $table = 'messages_parts';

    public $timestamps = true;
    protected $guarded = array();
    protected $hidden = array('created_at', 'updated_at', 'message_id');

    public function message()
    {
        return $this->belongsTo('Ddedic\Nexsell\Messages\Repositories\MessagePart', 'id');
    }    

    public function delivery_report()
    {
        return $this->hasOne('Ddedic\Nexsell\DeliveryReports\Repositories\DeliveryReportEloquentRepo', 'part_id');
    }





    public function getPartId()
    {
        return $this->id;
    }

    public function getNetwork()
    {
        return $this->network;
    }    

    public function getRecipient() {
        return $this->to;
    }

    public function getPrice()
    {
        return $this->price;
    }     

    public function getDeliveryReport()
    {
        return $this->delivery_report();
    }





}