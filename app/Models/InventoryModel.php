<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'inventory';
    protected $guarded = [];

    public function items()
    {
        return $this->belongsTo(ItemModel::class, 'id_item');
    }

    public function raks()
    {
        return $this->belongsTo(RakModel::class, 'id_rak');
    }

    public function receive()
    {
        return $this->belongsTo(ReceiveModel::class, 'id_receive');
    }

    public function pengeluaran()
    {
        return $this->belongsTo(PengeluaranModel::class, 'id_pengeluaran');
    }
}
