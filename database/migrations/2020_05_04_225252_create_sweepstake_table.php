<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSweepstakeTable extends Migration
{
	/**
		* Run the migrations.
		*
		* @return void
	*/
	public function up()
	{
		Schema::create('sweepstake', function (Blueprint $table) {
			$table->increments('id');
			$table->string('owner');
			$table->string('name');
			$table->enum('type', ['value', 'option_random', 'option_select']);
			$table->timestamps();
		});
	}

	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down()
	{
		Schema::dropIfExists('sweepstake');
	}
}
