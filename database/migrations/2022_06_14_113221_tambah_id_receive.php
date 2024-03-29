<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahIdReceive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('id_receive')->nullable()->after('id_item');
            $table->foreign('id_receive')->references('id')->on('receive');
            $table->double('qty_out')->default('0')->after('qty');
            $table->string('status_inventory', 15)->nullable()->after('qty_out');
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
            $table->dropForeign(['id_receive']);
            $table->dropColumn('id_receive');
            $table->dropColumn('qty_out');
            $table->dropColumn('status_inventory');
        });
    }
}
