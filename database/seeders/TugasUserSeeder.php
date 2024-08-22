<?php

namespace Database\Seeders;

use App\Models\Tugas;
use App\Models\TugasUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TugasUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = ['08.00 - 10.00', '10.00 - 11.30', '11.30 - 13.00', '13.00 - 14.00', '14.00 - 15.00',];
        $statuses = ['Tersedia', 'Ditutup'];
        foreach ($shifts as $shift) {
            foreach ($statuses as $status) {
                $tugasList = Tugas::where('status', $status)->where('shift', $shift)->get();
                $userAll = User::where('role', 'student')->where('shift', $shift)->where('is_verified', true)->get();

                foreach ($userAll as $user) {
                    if ($user->nickname === 'Syahran' || $user->role === 'admin') { continue; }

                    foreach ($tugasList as $tugas) {
                        TugasUser::firstOrCreate(
                            [
                                'tugas_id' => $tugas->id,
                                'user_id' => $user->id,
                                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                            ],
                            [
                                'id' => 'tugas-user-'.fake()->uuid(),
                                'note' => 'Ini ya bu tugasnya',
                                'status' => $status === 'Ditutup' ? 'Selesai' : 'Diperiksa',
                                'grade' => $status === 'Ditutup' ? rand(10, 20) * 5 : 0,
                            ]
                        );
                    }
                }
            }
        }
    }
}
