<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdGundag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rak', function (Blueprint $table) {
            $table->dropColumn('lokasi');
            $table->unsignedBigInteger('id_gudang')->nullable()->after('keterangan');
            $table->foreign('id_gudang')->references('id')->on('gudang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rak', function (Blueprint $table) {
            $table->string('lokasi', 64)->after('no_rak');
            $table->dropForeign(['id_gudang']);
            $table->dropColumn('id_gudang');
        });
    }
}
