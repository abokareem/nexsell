<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->string('api_key', 20);
			$table->string('api_secret', 20);
			$table->smallInteger('minute_limit')->default('30');
			$table->smallInteger('hour_limit')->default('1000');
			$table->smallInteger('plan_id')->index()->default('1');
			$table->float('credit_balance', 8,5);
			$table->smallInteger('active');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}