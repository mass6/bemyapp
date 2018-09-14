<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aeds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aed_id');
            $table->dateTime('creation_time');
            $table->string('organisation');
            $table->string('street_name');
            $table->string('street_number');
            $table->string('floor');
            $table->string('zip');
            $table->string('city');
            $table->boolean('inside');
            $table->boolean('outside');
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
        Schema::table('aeds', function (Blueprint $table) {
            //
        });
    }
}
