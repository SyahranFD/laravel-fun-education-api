<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'tugas_category_id',
        'title',
        'description',
        'deadline',
        'status',
    ];

    protected $casting = [
        'deadline' => 'date',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tugasCategory()
    {
        return $this->belongsTo(TugasCategory::class);
    }

    public function tugasImages()
    {
        return $this->hasMany(TugasImage::class);
    }
}
