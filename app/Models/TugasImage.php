<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasImage extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'tugas_id',
        'image',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
