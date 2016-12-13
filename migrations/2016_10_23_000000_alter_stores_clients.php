<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStoresClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('contact_name')->nullable();
            $table->integer('kiosks')->default(1);
            $table->string('address_street')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_postalcode')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_lat')->nullable();
            $table->string('address_lng')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('contact_name');
            $table->dropColumn('kiosks');
            $table->dropColumn('address_street');
            $table->dropColumn('address_number');
            $table->dropColumn('address_postalcode');
            $table->dropColumn('address_city');
            $table->dropColumn('address_lat');
            $table->dropColumn('address_lng');
            $table->dropColumn('email');
            $table->dropColumn('phone');
        });
    }
}
