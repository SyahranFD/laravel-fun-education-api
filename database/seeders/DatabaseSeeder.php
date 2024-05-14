<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $nameParts = explode(' ', Config::get('admin.nama'));
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';
        $profile_picture = 'https://ui-avatars.com/api/?name='.urlencode($firstName.' '.$lastName).'&color=7F9CF5&background=EBF4FF&size=128';

        User::create([
            'id' => 'user-'.fake()->uuid(),
            'nama_lengkap' => Config::get('admin.nama'),
            'password' => Hash::make(Config::get('admin.password')),
            'profile_picture' => $profile_picture,
            'role' => 'admin',
        ]);

        $rafa = User::create([
            'id' => 'user-'.fake()->uuid(),
            'nama_lengkap' => 'Syahran Fadhil Dafanindra',
            'password' => Hash::make('rafapass'),
            'profile_picture' => 'https://ui-avatars.com/api/?name=Syahran+Fadhil&color=7F9CF5&background=EBF4FF&size=128',
            'role' => 'student',
        ]);

        $rafa->shiftMasuk()->create([
            'id' => 'shift-masuk-'.fake()->uuid(),
            'user_id' => $rafa->id,
            'shift_masuk' => '08:00-10:00',
        ]);
    }
}
