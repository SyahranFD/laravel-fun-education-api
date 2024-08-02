<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\LaporanHarian;
use App\Models\Leaderboard;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LaporanHarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activityList = Activity::all();

        for ($i = 0; $i < 30; $i++) {
            foreach ($activityList as $activity) {
                foreach (User::all() as $user) {
                    $grade = ['A', 'B', 'C'][rand(0, 2)];
                    $point = $grade == 'A' ? 10 : ($grade == 'B' ? 4 : 3);
                    $date = Carbon::now()->subDays($i)->format('Y-m-d H:i:s');
                    if ($user->role === 'admin') { continue; }
                    $laporanHarian = LaporanHarian::create([
                        'activity_id' => $activity->id,
                        'id' => 'laporan-harian-'.Str::uuid(),
                        'user_id' => $user->id,
                        'grade' => $grade,
                        'point' => $point,
                        'note' => 'Sangat Bagus, Tetap Ditingkatkan ya Bu',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    Leaderboard::create([
                        'id' => 'leaderboard-'.Str::uuid(),
                        'user_id' => $user->id,
                        'laporan_harian_id' => $laporanHarian->id,
                        'point' => $point,
                        'created_at' => $date,
                    ]);
                }
            }
        }
    }
}
