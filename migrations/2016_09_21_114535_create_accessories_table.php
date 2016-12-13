<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_title');
            $table->string('slug');
            $table->integer('accessory_category_id')->unsigned();
            $table->integer('medium_id')->unsigned()->nullable();
            $table->timestamps();
            
            $table->foreign('accessory_category_id')->references('id')->on('accessory_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('medium_id')->references('id')->on('media')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accessories');
    }
}
