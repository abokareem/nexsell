<?php namespace Ddedic\Nexsell\DeliveryReports\Repositories;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Ddedic\Nexsell\DeliveryReports\DeliveryReportInterface;


class DeliveryReportEloquentRepo extends Eloquent implements DeliveryReportInterface {

    protected $table = 'delivery_reports';

    public $timestamps = true;
    protected $guarded = array();
    protected $hidden = array('id', 'part_id', 'created_at', 'updated_at');


    public function message_part()
    {
        return $this->belongsTo('Ddedic\Nexsell\Messages\Repositories\MessagePartEloquentRepo', 'id');
    }

    public function getStatus()
    {
        return $this->status;
    }   

    public function getDateTime()
    {
        return $this->time;
    }   


}