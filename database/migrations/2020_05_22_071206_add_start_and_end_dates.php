<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartAndEndDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

	    Schema::table('sweepstakes', function (Blueprint $table) {
		    $table->dateTime('start_date', 0)->nullable();
		    $table->dateTime('end_date', 0)->nullable();
	    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

	    Schema::table('sweepstakes', function (Blueprint $table) {
		    $table->dropColum(['start_date', 'end_date']);
	    });

    }
}
