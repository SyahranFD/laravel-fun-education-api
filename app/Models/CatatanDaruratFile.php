<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanDaruratFile extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function catatanDarurat()
    {
        return $this->belongsTo(CatatanDarurat::class);
    }
}
