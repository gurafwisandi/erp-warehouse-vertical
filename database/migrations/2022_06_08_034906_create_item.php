<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 64);
            $table->string('gambar', 64);
            $table->string('satuan', 64);
            $table->string('type', 64);
            $table->string('bentuk_barang', 64);
            $table->string('keterangan', 128);
            $table->unsignedBigInteger('id_vendor')->nullable();
            $table->foreign('id_vendor')->references('id')->on('vendor');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users');
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
        Schema::dropIfExists('item');
    }
}
