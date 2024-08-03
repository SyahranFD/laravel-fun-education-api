<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\CalendarCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $calendarCategories = [
            ['name' => 'Libur', 'color' => '#E13F3F'],
            ['name' => 'Kegiatan', 'color' => '#0CD644'],
            ['name' => 'Rekreasi', 'color' => '#FF7E5B'],
        ];

        $calendars = [
            ['id' => 'calendar-'.fake()->uuid(), 'calendar_category_id' => 1, 'title' => 'Hari Kemerdekaan', 'description' => 'Hari Kemerdekaan Indonesia', 'date' => '2024-08-17'],
            ['id' => 'calendar-'.fake()->uuid(), 'calendar_category_id' => 2, 'title' => 'Pertemuan Wali Murid', 'description' => 'Diharapkan Bapak/Ibu dapat menghadiri kegiatan ini', 'date' => '2024-08-19'],
            ['id' => 'calendar-'.fake()->uuid(), 'calendar_category_id' => 3, 'title' => 'Belajar di Museum', 'description' => 'Belajar ke Museum Raja Ali Haji', 'date' => '2024-08-30'],
        ];

        foreach ($calendarCategories as $calendarCategory) {
            CalendarCategory::create($calendarCategory);
        }

        foreach ($calendars as $calendar) {
            Calendar::create($calendar);
        }
    }
}
