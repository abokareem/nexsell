<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration {

	public function up()
	{
		Schema::create('messages', function(Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('message_id')->unique()->unsigned();
			$table->integer('client_id')->unsigned()->index();
			$table->integer('gateway_id')->unsigned()->index();
			$table->string('network', 20);
			$table->string('from', 128);
			$table->string('to', 900);
			$table->float('price_original', 8,5);
			$table->float('price', 8,5);
			$table->integer('status');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('messages');
	}
}