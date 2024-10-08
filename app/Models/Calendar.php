<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function calendarCategory()
    {
        return $this->belongsTo(CalendarCategory::class);
    }

    public function calendarFiles()
    {
        return $this->hasMany(CalendarFile::class);
    }
}
