<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasUser extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tugasUserImages()
    {
        return $this->hasMany(TugasUserImage::class);
    }

    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class);
    }
}
