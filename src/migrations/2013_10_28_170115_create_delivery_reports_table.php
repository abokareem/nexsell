<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryReportsTable extends Migration {

	public function up()
	{
		Schema::create('delivery_reports', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('message_id')->unsigned()->index();
			$table->integer('status');
			$table->integer('time')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('delivery_reports');
	}
}