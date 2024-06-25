<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Album;
use App\Models\CatatanDarurat;
use App\Models\Gallery;
use App\Models\User;
use App\Models\Activity;
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
            'username' => Config::get('admin.nama'),
            'tempat_tanggal_lahir' => 'Batam, 10 Agustus 1980',
            'alamat' => 'Griya Batu Aji Ari Blok G1, No 06',
            'password' => Hash::make(Config::get('admin.password')),
            'profile_picture' => $profile_picture,
            'role' => 'admin',
        ]);

        $rafa = User::create([
            'id' => 'user-'.fake()->uuid(),
            'username' => 'Syahran Fadhil',
            'tempat_tanggal_lahir' => 'Semarang, 15 Mei 2015',
            'alamat' => 'Jl. Kaliurang KM 5, Semarang',
            'password' => Hash::make('rafapass'),
            'profile_picture' => 'https://ui-avatars.com/api/?name=Syahran+Fadhil&color=7F9CF5&background=EBF4FF&size=128',
            'role' => 'student',
        ]);

        $rafa->shiftMasuk()->create([
            'id' => 'shift-masuk-'.fake()->uuid(),
            'user_id' => $rafa->id,
            'shift_masuk' => '08:00-10:00',
        ]);

        CatatanDarurat::create([
            'id' => 'catatan-darurat-'.fake()->uuid(),
            'catatan' => 'Diharapkan ananda membawa payung/jas hujan karena kondisi mendung.',
        ]);

        $activity1 = Activity::create([
            
            'name' => 'Datang Tepat Pada Waktunya',
        ]);

        $activity2 = Activity::create([
            
            'name' => 'Berpakaian Rapi',
        ]);

        $activity3 = Activity::create([
            
            'name' => 'Berbuat Baik Dengan Teman',
        ]);

        $activity4 = Activity::create([
            
            'name' => 'Mau Menolong dan Berbagi Dengan Teman',
        ]);

        $activity5 = Activity::create([
            
            'name' => 'Merapikan Alat Belajar dan Mainan Sendiri',
        ]);

        $activity6 = Activity::create([
            
            'name' => 'Menyelesaikan Tugas',
        ]);

        $activity7 = Activity::create([
            
            'name' => 'Membaca',
        ]);

        $activity8 = Activity::create([
            
            'name' => 'Menulis',
        ]);

        $activity9 = Activity::create([
            
            'name' => 'Dikte',
        ]);

        $activity10 = Activity::create([
            
            'name' => 'Keterampilan',
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 1,
            'grade' => 'A',
            'point' => 10,
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 2,
            'grade' => 'A',
            'point' => 10,
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 3,
            'grade' => 'B',
            'point' => 4,
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 4,
            'grade' => 'B',
            'point' => 4,
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 5,
            'grade' => 'C',
            'point' => 3,
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 6,
            'grade' => 'C',
            'point' => 3,
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 7,
            'grade' => 'B',
            'point' => 4,
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 8,
            'grade' => 'B',
            'point' => 4,
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 9,
            'grade' => 'A',
            'point' => 10,
        ]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'activity_id' => 10,
            'grade' => 'A',
            'point' => 10,
        ]);

        $rafa->laporanBulanan()->create([
            'id' => 'laporan-bulanan-'.fake()->uuid(),
            'status' => 'Berkembang',
            'catatan' => 'Ananda sudah sangat berkembang dibanding bulan lalu',
            'hal_yang_perlu_ditingkatkan' => 'Kemampuan membaca huruf R',
        ]);

        $rafa->alurBelajar()->create([
            'id' => 'alur-belajar-'.fake()->uuid(),
            'tahap' => 'B',
        ]);

        $rafa->savings()->create([
            'id' => 'saving-'.fake()->uuid(),
            'saving' => 250000,
        ]);

        $rafa->transaction()->create([
            'id' => 'transaction-'.fake()->uuid(),
            'category' => 'income',
            'amount' => 15000,
            'desc' => '',
            'created_at' => '2024-05-28 08:00:00',
        ]);

        $rafa->transaction()->create([
            'id' => 'transaction-'.fake()->uuid(),
            'category' => 'outcome',
            'amount' => 75000,
            'desc' => 'Untuk Bayar SPP',
            'created_at' => '2024-05-02 08:00:00',
        ]);

        $rafa->transaction()->create([
            'id' => 'transaction-'.fake()->uuid(),
            'category' => 'income',
            'amount' => 25000,
            'desc' => '',
            'created_at' => '2024-04-29 08:00:00',
        ]);

        $rafa->transaction()->create([
            'id' => 'transaction-'.fake()->uuid(),
            'category' => 'income',
            'amount' => 15000,
            'desc' => '',
            'created_at' => '2024-04-12 08:00:00',
        ]);

        $rafa->transaction()->create([
            'id' => 'transaction-'.fake()->uuid(),
            'category' => 'outcome',
            'amount' => 140000,
            'desc' => 'Untuk membayar biaya outbound',
            'created_at' => '2024-03-27 08:00:00',
        ]);

        $rafa->minimumApplication()->create([
            'id' => 'minimum-application-'.fake()->uuid(),
            'category' => 'SPP',
            'minimum' => 200000,
        ]);

        $rafa->minimumApplication()->create([
            'id' => 'minimum-application-'.fake()->uuid(),
            'category' => 'Kegiatan Belajar Diluar',
            'minimum' => 300000,
        ]);

        $album = Album::create([
            'id' => 'album-'.fake()->uuid(),
            'name' => 'Museum Batam Raja Ali Haji',
            'desc' => 'Kumpulan foto di museum raja ali haji',
        ]);

        Gallery::create([
            'id' => 'gallery-'.fake()->uuid(),
            'album_id' => $album->id,
            'image' => 'https://lh3.googleusercontent.com/p/AF1QipPFRtcGA5Ix9TJl2APPrZyUrcCWB7UjOSlDdB7Z=s1360-w1360-h1020',
            'title' => 'Foto dari Kejauhan',
            'description' => 'Foto dari Kejauhan Museum Raja Ali Haji',
        ]);

        Gallery::create([
            'id' => 'gallery-'.fake()->uuid(),
            'album_id' => $album->id,
            'image' => 'https://lh3.googleusercontent.com/p/AF1QipP_YwJA1mAC-yuqju3z4w5mXOXB-u2uzmrsHXIV=s1360-w1360-h1020',
            'title' => 'Sisi Depan',
            'description' => 'Sisi Depan Museum Raja Ali Haji',
        ]);

        Gallery::create([
            'id' => 'gallery-'.fake()->uuid(),
            'album_id' => $album->id,
            'image' => 'https://lh3.googleusercontent.com/p/AF1QipMuYixrQrLC8olvTgHpfQDdDrNKWiZ2eo43n55H=s1360-w1360-h1020',
            'title' => 'Aula Museum',
            'description' => 'Aula Museum Raja Ali Haji',
        ]);

        Gallery::create([
            'id' => 'gallery-'.fake()->uuid(),
            'album_id' => $album->id,
            'image' => 'https://lh3.googleusercontent.com/p/AF1QipMMac77s4KNdAP47FcsXvjzuVTto-leyzN1G6yB=s1360-w1360-h1020',
            'title' => 'Kapal',
            'description' => 'Kapal Raja Ali Haji',
        ]);

        Gallery::create([
            'id' => 'gallery-'.fake()->uuid(),
            'album_id' => $album->id,
            'image' => 'https://lh3.googleusercontent.com/p/AF1QipPiqY3hj3xCRHSNLAixR4ikjZ-2Vr-46muaNrw4=s1360-w1360-h1020',
            'title' => 'Baju Museum',
            'description' => 'Baju di Museum Raja Ali Haji',
        ]);

        Gallery::create([
            'id' => 'gallery-'.fake()->uuid(),
            'album_id' => $album->id,
            'image' => 'https://lh3.googleusercontent.com/p/AF1QipOy5KS2EsifJtHLP1Db1YpIAyS-97BfQgjdJegc=s1360-w1360-h1020',
            'title' => 'Peta Museum',
            'description' => 'Peta di Museum Raja Ali Haji',
        ]);

        Gallery::create([
            'id' => 'gallery-'.fake()->uuid(),
            'album_id' => $album->id,
            'image' => 'https://lh3.googleusercontent.com/p/AF1QipNqasP4C7KFnojiJXvNBTPo-9y2zUv3OrP5xVSm=s1360-w1360-h1020',
            'title' => 'Sejarah Kerajaan',
            'description' => 'Tulisan Sejarah Kerajaan di Museum Raja Ali Haji',
        ]);
    }
}
