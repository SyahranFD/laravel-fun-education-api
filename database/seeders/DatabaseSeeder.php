<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Album;
use App\Models\CatatanDarurat;
use App\Models\Gallery;
use App\Models\Saving;
use App\Models\SavingApplication;
use App\Models\ShiftMasuk;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TugasCategory;
use App\Models\Tugas;
use App\Models\TugasImage;
use App\Models\TugasUser;
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
        function randomDarkColor(){
            do { $r = mt_rand(50, 200);$g = mt_rand(50, 200);$b = mt_rand(50, 200);$brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
            } while ($brightness > 150);

            return sprintf('%02X%02X%02X', $r, $g, $b);
        }

        $background_color_random = randomDarkColor();
        $full_name_admin = Config::get('admin.nama');
        $nickname_admin = explode(' ', $full_name_admin)[0];
        $profile_picture_admin = 'https://ui-avatars.com/api/?name=' . urlencode($full_name_admin) . '&color=FFFFFF&background=' . $background_color_random . '&size=128';

        $admin = User::create(['id' => 'user-'.fake()->uuid(), 'full_name' => $full_name_admin, 'nickname' => $nickname_admin, 'birth' => 'Batam, 10 Agustus 1980', 'address' => 'Griya Batu Aji Ari Blok G1, No 06', 'password' => Hash::make(Config::get('admin.password')), 'gender' => 'Perempuan', 'profile_picture' => $profile_picture_admin,
            'role' => 'admin',]);
        $rafa = User::create(['id' => 'user-'.fake()->uuid(), 'full_name' => 'Syahran Fadhil', 'nickname' => 'Syahran', 'birth' => 'Semarang, 15 Mei 2015', 'address' => 'Jl. Kaliurang KM 5, Semarang', 'shift' => '08.00 - 10.00', 'password' => Hash::make('rafapass'), 'gender' => 'Laki-Laki', 'profile_picture' => 'https://ui-avatars.com/api/?name=Syahran+Fadhil&color=FFFFFF&background=' . $background_color_random . '&size=128',
            'role' => 'student',]);

        $shifts = ['08.00 - 10.00', '10.00 - 11.30', '11.30 - 13.00', '13.00 - 14.00', '14.00 - 15.00',];

        foreach ($shifts as $shift) {
            ShiftMasuk::create(['shift_masuk' => $shift]);
        }

        for ($i = 1; $i <= 30; $i++) {
            do {
                $full_name = implode(' ', array_slice(explode(' ', fake()->name), 0, 2));
                $nickname = explode(' ', $full_name)[0];
            } while (User::where('nickname', $nickname)->exists());
            $backgroundColor = randomDarkColor();
            $profile_picture_user = 'https://ui-avatars.com/api/?name=' . urlencode($full_name) . '&color=FFFFFF&background=' . $backgroundColor . '&size=128';
            $birth = fake()->dateTimeBetween('-10 years', '-6 years')->format('Y-m-d');

            $user = User::create(['id' => 'user-' . fake()->uuid(), 'full_name' => $full_name, 'nickname' => $nickname, 'birth' => $birth, 'address' => fake()->address, 'shift' => $shifts[($i - 1) % count($shifts)], 'password' => Hash::make('pass'), 'gender' => fake()->randomElement(['Laki-Laki', 'Perempuan']), 'profile_picture' => $profile_picture_user, 'role' => 'student',]);

            $randomBase = rand(20, 50);
            $tabungan = $randomBase * 10000;
            $tabunganFinal = $tabungan - 100000;
            $transaksi = $tabungan / 2;

            Saving::create(['id' => 'saving-' . fake()->uuid(), 'user_id' => $user->id, 'saving' => $tabunganFinal,]);
            SavingApplication::create(['id' => 'saving-application-' . fake()->uuid(), 'user_id' => $user->id, 'category' => 'SPP', 'status' => 'pending',]);
            Transaction::create(['id' => 'transaction-' . fake()->uuid(), 'user_id' => $user->id, 'category' => 'income', 'amount' => $transaksi, 'created_at' => fake()->dateTimeBetween('-1 month', 'now'),]);
            Transaction::create(['id' => 'transaction-' . fake()->uuid(), 'user_id' => $user->id, 'category' => 'income', 'amount' => $transaksi, 'created_at' => fake()->dateTimeBetween('-1 month', 'now'),]);
            Transaction::create(['id' => 'transaction-' . fake()->uuid(), 'user_id' => $user->id, 'category' => 'outcome', 'amount' => 100000, 'created_at' => fake()->dateTimeBetween('-1 month', 'now'), 'desc' => 'Untuk Bayar SPP',]);
        }

        CatatanDarurat::create(['id' => 'catatan-darurat-'.fake()->uuid(), 'catatan' => 'Diharapkan ananda membawa payung/jas hujan karena kondisi mendung.',]);

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

        $rafa->alurBelajar()->create(['id' => 'alur-belajar-'.fake()->uuid(),
            'tahap' => 'B',]);

        $rafa->savings()->create(['id' => 'saving-'.fake()->uuid(),
            'saving' => 250000,]);

        $rafa->transaction()->create(['id' => 'transaction-'.fake()->uuid(),
            'category' => 'income',
            'amount' => 15000,
            'created_at' => '2024-05-28 08:00:00',]);
        $rafa->transaction()->create(['id' => 'transaction-'.fake()->uuid(),
            'category' => 'outcome',
            'amount' => 75000,
            'desc' => 'Untuk Bayar SPP',
            'created_at' => '2024-05-02 08:00:00',]);
        $rafa->transaction()->create(['id' => 'transaction-'.fake()->uuid(),
            'category' => 'income',
            'amount' => 25000,
            'created_at' => '2024-04-29 08:00:00',]);
        $rafa->transaction()->create(['id' => 'transaction-'.fake()->uuid(),
            'category' => 'income',
            'amount' => 15000,
            'created_at' => '2024-04-12 08:00:00',]);

        $rafa->transaction()->create(['id' => 'transaction-'.fake()->uuid(),
            'category' => 'outcome',
            'amount' => 140000,
            'desc' => 'Untuk membayar biaya outbound',
            'created_at' => '2024-03-27 08:00:00',]);
        $rafa->minimumApplication()->create(['id' => 'minimum-application-'.fake()->uuid(),
            'category' => 'SPP',
            'minimum' => 200000,]);

        $rafa->minimumApplication()->create(['id' => 'minimum-application-'.fake()->uuid(),
            'category' => 'Kegiatan Belajar Diluar',
            'minimum' => 300000,]);

        $album = Album::create(['id' => 'album-'.fake()->uuid(),
            'name' => 'Museum Batam Raja Ali Haji',
            'desc' => 'Kumpulan foto di museum raja ali haji',]);

        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipPFRtcGA5Ix9TJl2APPrZyUrcCWB7UjOSlDdB7Z=s1360-w1360-h1020', 'title' => 'Foto dari Kejauhan', 'description' => 'Foto dari Kejauhan Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipP_YwJA1mAC-yuqju3z4w5mXOXB-u2uzmrsHXIV=s1360-w1360-h1020', 'title' => 'Sisi Depan', 'description' => 'Sisi Depan Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipMuYixrQrLC8olvTgHpfQDdDrNKWiZ2eo43n55H=s1360-w1360-h1020', 'title' => 'Aula Museum', 'description' => 'Aula Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipMMac77s4KNdAP47FcsXvjzuVTto-leyzN1G6yB=s1360-w1360-h1020', 'title' => 'Kapal', 'description' => 'Kapal Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipPiqY3hj3xCRHSNLAixR4ikjZ-2Vr-46muaNrw4=s1360-w1360-h1020', 'title' => 'Baju Museum', 'description' => 'Baju di Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipOy5KS2EsifJtHLP1Db1YpIAyS-97BfQgjdJegc=s1360-w1360-h1020', 'title' => 'Peta Museum', 'description' => 'Peta di Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipNqasP4C7KFnojiJXvNBTPo-9y2zUv3OrP5xVSm=s1360-w1360-h1020', 'title' => 'Sejarah Kerajaan', 'description' => 'Tulisan Sejarah Kerajaan di Museum Raja Ali Haji',]);

        $tugasCategory1 = TugasCategory::create(['id' => 1, 'name' => 'Dikte & Menulis',]);
        $tugasCategory2 = TugasCategory::create(['id' => 2, 'name' => 'Kreasi',]);
        $tugasCategory3 = TugasCategory::create(['id' => 3, 'name' => 'Membaca',]);
        $tugasCategory4 = TugasCategory::create(['id' => 4, 'name' => 'Berhitung',]);

        $tugas = Tugas::create(['id' => 'tugas-'.fake()->uuid(), 'tugas_category_id' => $tugasCategory1->id,
            'title' => 'Menulis 5 benda yang sering dilihat oleh ananda',
            'description' => 'Berdasarkan gambar tersebut ambil lima barang yang ingin didiktekan, setelah selesai foto hasil tugas anak lalu kumpulkan.',
            'deadline' => '2024-06-30',]);

        TugasImage::create(['id' => 'tugas-image-'.fake()->uuid(), 'tugas_id' => $tugas->id,
            'image' => 'https://lh3.googleusercontent.com/p/AF1QipPFRtcGA5Ix9TJl2APPrZyUrcCWB7UjOSlDdB7Z=s1360-w1360-h1020',]);

        TugasUser::create(['id' => 'tugas-user-'.fake()->uuid(), 'tugas_id' => $tugas->id, 'user_id' => $rafa->id, 'note' => 'sudah lumayan bu']);
    }
}
