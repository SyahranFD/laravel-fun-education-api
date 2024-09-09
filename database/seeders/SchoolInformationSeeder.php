<?php

namespace Database\Seeders;

use App\Models\SchoolInformation;
use App\Models\SchoolInformationDesc;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $si1 = SchoolInformation::create(['title' => 'Libur Siswa', 'id' => 'school-information-'.fake()->uuid(), 'created_at' => '2024-01-04']);
        SchoolInformationDesc::create(['body' => 'Hari Minggu/Ahad', 'school_information_id' => $si1->id, 'id' => 'school-information-desc-'.fake()->uuid()]);
        SchoolInformationDesc::create(['body' => 'Tanggal merah /Libur Nasional', 'school_information_id' => $si1->id, 'id' => 'school-information-desc-'.fake()->uuid()]);

        $si2 = SchoolInformation::create(['title' => 'Seragam', 'id' => 'school-information-'.fake()->uuid(), 'created_at' => '2024-01-03']);
        SchoolInformationDesc::create(['body' => 'WAJIB PAKAI setiap pertemuan', 'school_information_id' => $si2->id, 'id' => 'school-information-desc-'.fake()->uuid()]);

        $si3 = SchoolInformation::create(['title' => 'Menabung', 'id' => 'school-information-'.fake()->uuid(), 'created_at' => '2024-01-02']);
        SchoolInformationDesc::create(['body' => 'TIDAK WAJIB / bagi yang mau saja', 'school_information_id' => $si3->id, 'id' => 'school-information-desc-'.fake()->uuid()]);
        SchoolInformationDesc::create(['body' => 'Kegiatan menabung setiap hari Selasa dan Kamis', 'school_information_id' => $si3->id, 'id' => 'school-information-desc-'.fake()->uuid()]);
        SchoolInformationDesc::create(['body' => 'Diluar dari jadwal itu tidak kami terima.', 'school_information_id' => $si3->id, 'id' => 'school-information-desc-'.fake()->uuid()]);

        $si4 = SchoolInformation::create(['title' => 'Kegiatan', 'id' => 'school-information-'.fake()->uuid(), 'created_at' => '2024-01-01']);
        SchoolInformationDesc::create(['body' => 'Setiap hari Kamis, membawa buku gambar dan pewarna. untuk  melatih motorik halus,motorik kasar, konsentrasi, dan kognitif  anak.', 'school_information_id' => $si4->id, 'id' => 'school-information-desc-'.fake()->uuid()]);
    }
}
