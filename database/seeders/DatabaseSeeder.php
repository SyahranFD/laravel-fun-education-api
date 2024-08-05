<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Album;
use App\Models\AlurBelajar;
use App\Models\CatatanDarurat;
use App\Models\Gallery;
use App\Models\LaporanHarian;
use App\Models\Leaderboard;
use App\Models\Saving;
use App\Models\SavingApplication;
use App\Models\ShiftMasuk;
use App\Models\Transaction;
use App\Models\TugasUserImage;
use App\Models\User;
use App\Models\Tugas;
use App\Models\TugasImage;
use App\Models\TugasUser;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        CatatanDarurat::create(['id' => 'catatan-darurat-'.fake()->uuid(), 'catatan' => 'Diharapkan ananda membawa payung/jas hujan karena kondisi mendung.',]);

        $this->call([
            UserSeeder::class,
            ActivitySeeder::class,
            TugasSeeder::class,
            TugasUserSeeder::class,
            RafaSeeder::class,
            LaporanHarianSeeder::class,
            GallerySeeder::class,
            CalendarSeeder::class
        ]);
    }
}
