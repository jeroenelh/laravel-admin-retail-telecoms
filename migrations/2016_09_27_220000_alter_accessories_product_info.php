<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAccessoriesProductInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accessories', function (Blueprint $table) {
            $table->string('article_number')->nullable();
            $table->string('ean')->nullable();
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('price');
            $table->string('created_by')->default('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accessories', function (Blueprint $table) {
            $table->dropColumn('article_number');
            $table->dropColumn('ean');
            $table->dropColumn('sku');
            $table->dropColumn('description');
            $table->dropColumn('brand');
            $table->dropColumn('price');
            $table->dropColumn('created_by');
        });
    }
}
