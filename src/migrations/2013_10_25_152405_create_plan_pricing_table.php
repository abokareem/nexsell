<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlanPricingTable extends Migration {

	public function up()
	{
		Schema::create('plan_pricing', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('plan_id')->unsigned()->index();
			$table->string('country_code', 3)->index();
			$table->integer('operator');
			$table->float('price_original', 8,5);
			$table->enum('price_adjustment_type', array('percentage', 'fixed'));
			$table->float('price_adjustment_value', 8,5);
		});
	}

	public function down()
	{
		Schema::drop('plan_pricing');
	}
}