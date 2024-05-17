<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_lengkap',
        'password',
        'tempat_tanggal_lahir',
        'alamat',
        'profile_picture',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function shiftMasuk()
    {
        return $this->hasOne(ShiftMasuk::class);
    }

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class);
    }
}
