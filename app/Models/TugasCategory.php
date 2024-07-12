<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasCategory extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
}
