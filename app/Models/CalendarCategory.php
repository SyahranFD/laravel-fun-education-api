<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarCategory extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }
}
