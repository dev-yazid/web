<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('sub_category_id')->unsigned();
            $table->string('image');
            $table->string('price');
            $table->text('content');
            $table->timestamps();
        });
        
        Schema::table('options', function($table) {
            $table->foreign('sub_category_id')->references('id')->on('sub_categories');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('options');
    }
}
