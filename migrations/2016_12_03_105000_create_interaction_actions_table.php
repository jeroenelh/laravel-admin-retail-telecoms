<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInteractionActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interaction_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('interaction_id')->unsigned();
            $table->morphs('interactionable', 'log');
            $table->timestamps();

            $table->foreign('interaction_id')->references('id')->on('interactions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('interaction_actions');
    }
}
