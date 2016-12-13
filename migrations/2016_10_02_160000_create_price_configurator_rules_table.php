<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceConfiguratorRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_configurator_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('price_configurator_id')->unsigned();
            $table->string('field');
            $table->string('operation');
            $table->string('value');
            $table->timestamps();

            $table->foreign('price_configurator_id')->references('id')->on('price_configurators')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('price_configurator_rules');
    }
}
