<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiveDetailModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'receive_detail';
    protected $guarded = [];

    public function items()
    {
        return $this->belongsTo(ItemModel::class, 'id_item');
    }
}
