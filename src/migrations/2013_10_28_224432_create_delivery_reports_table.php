<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryReportsTable extends Migration {

	public function up()
	{
		Schema::create('delivery_reports', function(Blueprint $table) {
			$table->increments('id');
			$table->string('part_id', 50)->unique();
			$table->integer('status');
			$table->integer('time')->unsigned();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('delivery_reports');
	}
}