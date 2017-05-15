<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setttings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('facebook');
            $table->string('instagram');
            $table->string('twitter');
            $table->string('vivo');
            $table->string('email');
            $table->string('logo');
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
        Schema::drop('setttings');
    }
}
