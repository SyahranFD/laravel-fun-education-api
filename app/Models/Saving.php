<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'saving',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
