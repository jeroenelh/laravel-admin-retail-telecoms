<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessoryCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessory_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_title');
            $table->string('slug');
            $table->integer('medium_id')->unsigned()->nullable();
            $table->integer('store_id')->unsigned();
            $table->timestamps();

            $table->foreign('medium_id')->references('id')->on('media')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('store_id')->references('id')->on('stores')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accessory_categories');
    }
}
