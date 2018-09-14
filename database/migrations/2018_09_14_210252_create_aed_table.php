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
        Schema::create('aeds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aed_id');
            $table->dateTime('creation_time');
            $table->string('organisation');
            $table->string('street_name');
            $table->string('street_number');
            $table->string('floor');
            $table->string('zip');
            $table->string('city');
            $table->string('inside');
            $table->string('outside');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('municipality');
            $table->string('region');
            $table->boolean('available_247');
            $table->string('location_type');
            $table->string('location_category');
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
        Schema::dropIfExists('aeds');
    }
}
