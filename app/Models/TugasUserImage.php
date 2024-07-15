<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasUserImage extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function tugasUser()
    {
        return $this->belongsTo(TugasUser::class);
    }
}
