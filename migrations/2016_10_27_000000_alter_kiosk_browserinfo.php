<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterKioskBrowserinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kiosks', function (Blueprint $table) {
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os')->nullable();
            $table->string('os_version')->nullable();
            $table->string('device')->nullable();
            $table->string('language')->nullable();
            $table->boolean('need_refresh')->default(false);
            $table->boolean('need_reload_images')->default(false);
            $table->boolean('need_reload_accessories')->default(false);
            $table->dateTime('last_connection')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kiosks', function (Blueprint $table) {
            $table->dropColumn('browser');
            $table->dropColumn('os');
            $table->dropColumn('need_refresh');
            $table->dropColumn('need_reload_images');
            $table->dropColumn('need_reload_accessories');
            $table->dropColumn('last_connection');
        });
    }
}
