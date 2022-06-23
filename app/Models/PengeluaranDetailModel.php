<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengeluaranDetailModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pengeluaran_detail';
    protected $guarded = [];

    public function inventorys()
    {
        return $this->belongsTo(InventoryModel::class, 'id_inventory');
    }

    public function items()
    {
        return $this->belongsTo(ItemModel::class, 'id_item');
    }
}
