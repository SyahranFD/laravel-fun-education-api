<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanDarurat extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'catatan',
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];
}
