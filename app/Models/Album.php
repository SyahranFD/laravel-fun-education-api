<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }
}
