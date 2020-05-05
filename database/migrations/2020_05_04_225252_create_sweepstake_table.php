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
			$table->enum('type', ['value', 'random_option', 'select_option']);
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
