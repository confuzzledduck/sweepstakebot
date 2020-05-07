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
		Schema::create('sweepstakes', function (Blueprint $table) {

			$table->increments('id');
			$table->string('owner', 30);
			$table->string('name');
			$table->enum('type', ['value', 'option_random', 'option_select']);
			$table->timestamps();
			
			$table->index('owner');
			
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
