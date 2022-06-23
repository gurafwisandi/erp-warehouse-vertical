<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengeluaranDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengeluaran_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pengeluaran')->nullable();
            $table->foreign('id_pengeluaran')->references('id')->on('pengeluaran');
            $table->unsignedBigInteger('id_inventory')->nullable();
            $table->foreign('id_inventory')->references('id')->on('inventory');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengeluaran_detail');
    }
}
