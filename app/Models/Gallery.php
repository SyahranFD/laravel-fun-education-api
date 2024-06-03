<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'album_id',
        'image',
        'title',
        'description',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
