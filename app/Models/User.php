<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Config;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_lengkap',
        'password',
        'profile_picture'
    ];

    protected $hidden = [
        'password',
    ];

    public function isAdmin()
    {
        $adminNama = Config::get('admin.nama');
        $adminPassword = Config::get('admin.password');
        return $this->nama_lengkap === $adminNama && $this->password === $adminPassword;
    }
}
