<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activity::create(['name' => 'Datang Tepat Pada Waktunya',]);
        Activity::create(['name' => 'Berpakaian Rapi',]);
        Activity::create(['name' => 'Berbuat Baik Dengan Teman',]);
        Activity::create(['name' => 'Mau Menolong dan Berbagi Dengan Teman',]);
        Activity::create(['name' => 'Merapikan Alat Belajar dan Mainan Sendiri',]);
        Activity::create(['name' => 'Menyelesaikan Tugas',]);
        Activity::create(['name' => 'Membaca',]);
        Activity::create(['name' => 'Menulis',]);
        Activity::create(['name' => 'Dikte',]);
        Activity::create(['name' => 'Keterampilan',]);
    }
}
