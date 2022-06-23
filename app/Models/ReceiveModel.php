<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiveModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'receive';
    protected $guarded = [];

    public function vendors()
    {
        return $this->belongsTo(SupplierModel::class, 'id_vendor');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
