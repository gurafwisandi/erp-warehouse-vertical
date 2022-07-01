<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RakModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'rak';
    protected $guarded = [];

    public function items()
    {
        return $this->belongsTo(ItemModel::class, 'id_item');
    }

    public function gudang()
    {
        return $this->belongsTo(GudangModel::class, 'id_gudang');
    }
}
