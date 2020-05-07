<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSweepstakeOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sweepstake_options', function (Blueprint $table) {
        	
            $table->increments('id');
            $table->unsignedInteger('sweepstake_id');
            $table->string('option');
            $table->timestamps();
            
            $table->foreign('sweepstake_id')->references('id')->on('sweepstakes');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sweepstake_options');
    }
}
