<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahDetailType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receive_detail', function (Blueprint $table) {
            $table->string('type', 64)->after('id_rak');
            $table->dropForeign(['id_rak']);
            $table->dropColumn('id_rak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receive_detail', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->unsignedBigInteger('id_rak')->nullable();
            $table->foreign('id_rak')->references('id')->on('rak');
        });
    }
}
