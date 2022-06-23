<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahItemDanStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengeluaran_detail', function (Blueprint $table) {
            $table->string('status_out', 25)->nullable()->after('id_inventory');
            $table->string('type_out', 25)->nullable()->after('id_inventory');
            $table->double('qty')->nullable()->after('id_inventory');
            $table->unsignedBigInteger('id_rak')->nullable()->after('id_inventory');
            $table->foreign('id_rak')->references('id')->on('rak');
            $table->unsignedBigInteger('id_item')->nullable()->after('id_inventory');
            $table->foreign('id_item')->references('id')->on('item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengeluaran_detail', function (Blueprint $table) {
            $table->dropForeign(['id_item']);
            $table->dropColumn('id_item');
            $table->dropForeign(['id_rak']);
            $table->dropColumn('id_rak');
            $table->dropColumn('qty');
            $table->dropColumn('type_out');
            $table->dropColumn('status_out');
        });
    }
}
