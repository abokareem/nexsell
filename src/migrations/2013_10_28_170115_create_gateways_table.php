<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGatewaysTable extends Migration {

	public function up()
	{
		Schema::create('gateways', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('description', 500);
			$table->string('class_name', 255);
			$table->string('api_key', 50);
			$table->string('api_secret', 50);
			$table->tinyInteger('active');
		});
	}

	public function down()
	{
		Schema::drop('gateways');
	}
}