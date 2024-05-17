<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'datang_tepat_pada_waktunya',
        'berpakaian_rapi',
        'berbuat_baik_dengan_teman',
        'mau_menolong_dan_berbagi_dengan_teman',
        'merapikan_alat_belajar_dan_mainan_sendiri',
        'menyelesaikan_tugas',
        'membaca',
        'menulis',
        'dikte',
        'keterampilan',
    ];

    protected $casting = [
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
