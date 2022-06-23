<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengeluaranModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pengeluaran';
    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
