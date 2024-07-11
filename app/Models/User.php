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
        'full_name',
        'nickname',
        'birth',
        'address',
        'shift',
        'gender',
        'password',
        'profile_picture',
        'role',
        'fcm_token',
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

    public function laporanBulanan()
    {
        return $this->hasMany(LaporanBulanan::class);
    }

    public function alurBelajar()
    {
        return $this->hasOne(AlurBelajar::class);
    }

    public function savings()
    {
        return $this->hasOne(Saving::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function savingApplication()
    {
        return $this->hasMany(SavingApplication::class);
    }

    public function minimumApplication()
    {
        return $this->hasMany(MinimumApplication::class);
    }

    public function tugasUser()
    {
        return $this->hasMany(TugasUser::class);
    }
}
