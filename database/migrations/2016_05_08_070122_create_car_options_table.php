<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('car_options', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('car_id')->unsigned();
                $table->integer('option_id')->unsigned();
                $table->timestamps();
            });
           
            Schema::table('car_options', function($table) {
                $table->foreign('option_id')->references('id')->on('options');
                $table->foreign('car_id')->references('id')->on('cars');
            });
                                
         /*   Schema::table('car_options', function($table) {
                $table->foreign('car_id')->references('id')->on('cars');
            });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('car_options');
    }
}
