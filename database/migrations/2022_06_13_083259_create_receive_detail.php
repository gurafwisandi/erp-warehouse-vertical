<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiveDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_receive')->nullable();
            $table->foreign('id_receive')->references('id')->on('receive');
            $table->unsignedBigInteger('id_item')->nullable();
            $table->foreign('id_item')->references('id')->on('item');
            $table->double('qty')->nullable();
            $table->double('qty_terima')->nullable();
            $table->date('tgl_produksi')->nullable();
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
        Schema::dropIfExists('receive_detail');
    }
}
