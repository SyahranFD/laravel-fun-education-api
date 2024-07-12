<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    protected $casting = [
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
