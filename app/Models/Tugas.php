<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function tugasImages()
    {
        return $this->hasMany(TugasImage::class);
    }

    public function tugasUsers()
    {
        return $this->hasMany(TugasUser::class);
    }

    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class);
    }
}
