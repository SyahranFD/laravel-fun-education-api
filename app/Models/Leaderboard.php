<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporanHarian()
    {
        return $this->belongsTo(LaporanHarian::class);
    }

    public function tugasUser()
    {
        return $this->belongsTo(TugasUser::class);
    }
}
