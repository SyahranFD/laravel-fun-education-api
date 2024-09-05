<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInformation extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    public function schoolInformationDesc()
    {
        return $this->belongsTo(SchoolInformationDesc::class);
    }
}
