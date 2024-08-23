<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarFile extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
}
