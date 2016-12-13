<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKioskActivationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kiosk_activations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kiosk_id')->unsigned();
            $table->string('activation_key', 6);
            $table->boolean('is_used')->default(0);
            $table->boolean('is_expired')->default(0);
            $table->timestamps();

            $table->foreign('kiosk_id')->references('id')->on('kiosks')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('kiosk_activations');
    }
}
