<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepairCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_title');
            $table->string('slug');
            $table->text('description');
            $table->integer('medium_id')->unsigned()->nullable();
            $table->timestamps();

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
        Schema::drop('repair_categories');
    }
}
