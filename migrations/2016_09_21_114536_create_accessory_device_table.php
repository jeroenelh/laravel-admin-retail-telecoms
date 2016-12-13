<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessoryDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accessory_device', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('accessory_id')->unsigned();
            $table->integer('device_id')->unsigned();
            $table->timestamps();

            $table->foreign('accessory_id')->references('id')->on('accessories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('devices')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accessory_device');
    }
}
