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
        'tugas_category_id',
        'title',
        'description',
        'deadline',
        'status',
        'grade',
        'parent_note'
    ];

    public function tugasCategory()
    {
        return $this->belongsTo(TugasCategory::class);
    }

    public function tugasImages()
    {
        return $this->hasMany(TugasImage::class);
    }
}
