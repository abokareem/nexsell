<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesPartsTable extends Migration {

	public function up()
	{
		Schema::create('messages_parts', function(Blueprint $table) {
			$table->string('id', 50)->primary();
			$table->integer('message_id')->unsigned()->index();
			$table->string('network', 20);
			$table->bigInteger('to')->unsigned();
			$table->float('price', 8,5);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('messages_parts');
	}
}