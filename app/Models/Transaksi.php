<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'jenis',
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
