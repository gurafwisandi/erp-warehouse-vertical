<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IdPengeluaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pengeluaran')->nullable()->after('id_receive_detail');
            $table->foreign('id_pengeluaran')->references('id')->on('pengeluaran');
            $table->unsignedBigInteger('id_pengeluaran_detail')->nullable()->after('id_pengeluaran');
            $table->foreign('id_pengeluaran_detail')->references('id')->on('pengeluaran_detail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropForeign(['id_pengeluaran']);
            $table->dropColumn('id_pengeluaran');
            $table->dropForeign(['id_pengeluaran_detail']);
            $table->dropColumn('id_pengeluaran_detail');
        });
    }
}
