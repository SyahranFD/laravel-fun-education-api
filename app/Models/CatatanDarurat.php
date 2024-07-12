<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanDarurat extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'is_deleted' => 'boolean',
    ];
}
