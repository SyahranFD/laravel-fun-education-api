<?php

namespace Database\Seeders;

use App\Models\AlurBelajar;
use App\Models\Saving;
use App\Models\Transaction;
use App\Models\Tugas;
use App\Models\TugasUser;
use App\Models\TugasUserImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RafaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rafa = User::create([
            'id' => 'user-'.fake()->uuid(),
            'full_name' => 'Syahran Fadhil',
            'nickname' => 'Syahran',
            'email' => 'fadhilrafa1@gmail.com',
            'birth' => 'Semarang, 15 Mei 2015',
            'address' => 'Jl. Kaliurang KM 5, Semarang',
            'shift' => '08.00 - 10.00',
            'password' => Hash::make('rafapass'),
            'gender' => 'Laki-Laki',
            'profile_picture' => 'https://ui-avatars.com/api/?name=Syahran+Fadhil&color=FFFFFF&background=47E5BC&size=128',
            'role' => 'student',
            'is_verified' => true,
            'is_verified_email' => true,
        ]);

        AlurBelajar::create(['id' => 'alur-belajar-'.fake()->uuid(), 'user_id' => $rafa->id, 'tahap' => 'B',]);
        Saving::create(['id' => 'saving-'.fake()->uuid(), 'user_id' => $rafa->id, 'saving' => 250000,]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'amount' => 15000, 'category' => 'income', 'created_at' => '2024-07-19',]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'category' => 'income', 'amount' => 25000, 'created_at' => '2024-07-18',]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'category' => 'income', 'amount' => 15000, 'created_at' => '2024-07-17',]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'category' => 'outcome', 'amount' => 75000, 'desc' => 'Untuk Bayar SPP', 'created_at' => '2024-07-17',]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'category' => 'outcome', 'amount' => 140000, 'desc' => 'Untuk membayar biaya outbound', 'created_at' => '2024-07-16',]);

        $tugasRafaList = Tugas::where('status', 'Tersedia')->where('shift', $rafa->shift)->orderBy('created_at', 'desc')->get();
        $rafaTugasUser1 = TugasUser::create(['status' => 'Diperiksa', 'id' => 'tugas-user-'.fake()->uuid(), 'tugas_id' => $tugasRafaList[2]->id, 'user_id' => $rafa->id, 'note' => 'Ini ya bu tugasnya',]);
        $rafaTugasUser2 = TugasUser::create(['status' => 'Selesai', 'id' => 'tugas-user-'.fake()->uuid(), 'tugas_id' => $tugasRafaList[3]->id, 'user_id' => $rafa->id, 'note' => 'Ini ya bu tugasnya', 'grade' => 100, 'created_at' => Carbon::now()->subDays(rand(0, 3)),]);
        TugasUserImage::create(['id' => 'tugas-user-image-'.fake()->uuid(), 'tugas_user_id' => $rafaTugasUser1->id, 'image' => 'https://i.ytimg.com/vi/b2q4Pc8f7jM/hqdefault.jpg']);
        TugasUserImage::create(['id' => 'tugas-user-image-'.fake()->uuid(), 'tugas_user_id' => $rafaTugasUser2->id, 'image' => 'https://i.ytimg.com/vi/b2q4Pc8f7jM/hqdefault.jpg']);

        $tugasRafaDitutup = Tugas::where('status', 'Ditutup')->where('shift', $rafa->shift)->orderBy('created_at', 'desc')->get();
        foreach ($tugasRafaDitutup as $tugas) {
            $created_at = Carbon::now()->subDays(rand(0, 30));
            TugasUser::firstOrCreate(
                [
                    'tugas_id' => $tugas->id,
                    'user_id' => $rafa->id,
                    'created_at' => $created_at->format('Y-m-d H:i:s'),
                ],
                [
                    'id' => 'tugas-user-'.fake()->uuid(),
                    'note' => 'Ini ya bu tugasnya',
                    'status' => 'Selesai',
                    'grade' => rand(10, 20) * 5,
                ]
            );
        }
    }
}
