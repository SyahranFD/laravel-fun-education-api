<?php

namespace Database\Seeders;

use App\Models\ShiftMasuk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShiftMasuk::create(['shift_masuk' => '08.00 - 10.00',]);
        ShiftMasuk::create(['shift_masuk' => '10.00 - 11.30',]);
        ShiftMasuk::create(['shift_masuk' => '11.30 - 13.00',]);
        ShiftMasuk::create(['shift_masuk' => '13.00 - 14.00',]);
        ShiftMasuk::create(['shift_masuk' => '14.00 - 15.00',]);
    }
}
