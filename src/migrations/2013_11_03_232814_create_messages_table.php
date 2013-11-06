<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration {

	public function up()
	{
		Schema::create('messages', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('client_id')->unsigned()->index();
			$table->integer('gateway_id')->unsigned()->index();
			$table->string('country_code', 3)->index();
			$table->string('from', 128);
			$table->string('to', 900);
			$table->string('text', 800)->nullable();
			$table->float('price', 8,5);
			$table->enum('status', array('pending','queued', 'sent', 'failed'));
			$table->string('status_msg', 255)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('messages');
	}
}