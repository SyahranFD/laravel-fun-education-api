<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Album;
use App\Models\CatatanDarurat;
use App\Models\Gallery;
use App\Models\ShiftMasuk;
use App\Models\User;
use App\Models\TugasCategory;
use App\Models\Tugas;
use App\Models\TugasImage;
use App\Models\TugasUser;
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
        $nickname_admin = $nickname = explode(' ', $full_name_admin)[0];
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
            $full_name = implode(' ', array_slice(explode(' ', fake()->name), 0, 2));
            $nickname = explode(' ', $full_name)[0];
            $backgroundColor = randomDarkColor();
            $profile_picture_user = 'https://ui-avatars.com/api/?name=' . urlencode($full_name) . '&color=FFFFFF&background=' . $backgroundColor . '&size=128';
            $birth = fake()->dateTimeBetween('-10 years', '-6 years')->format('Y-m-d');

            User::create(['id' => 'user-' . fake()->uuid(), 'full_name' => $full_name, 'nickname' => $nickname, 'birth' => $birth, 'address' => fake()->address, 'shift' => $shifts[($i - 1) % count($shifts)], 'password' => Hash::make('pass'), 'gender' => fake()->randomElement(['Laki-Laki', 'Perempuan']), 'profile_picture' => $profile_picture_user, 'role' => 'student',]);
        }

        CatatanDarurat::create(['id' => 'catatan-darurat-'.fake()->uuid(), 'catatan' => 'Diharapkan ananda membawa payung/jas hujan karena kondisi mendung.',]);

        $rafa->laporanHarian()->create([
            'id' => 'laporan-harian-'.fake()->uuid(),
            'user_id' => $rafa->id,
            'datang_tepat_pada_waktunya' => chr(rand(65, 67)), // A-C
            'berpakaian_rapi' => chr(rand(65, 67)), // A-C
            'berbuat_baik_dengan_teman' => chr(rand(65, 67)), // A-C
            'mau_menolong_dan_berbagi_dengan_teman' => chr(rand(65, 67)), // A-C
            'merapikan_alat_belajar_dan_mainan_sendiri' => chr(rand(65, 67)), // A-C
            'menyelesaikan_tugas' => chr(rand(65, 67)), // A-C
            'membaca' => chr(rand(65, 67)), // A-C
            'menulis' => chr(rand(65, 67)), // A-C
            'dikte' => chr(rand(65, 67)), // A-C
            'keterampilan' => chr(rand(65, 67)), // A-C
        ]);

        $rafa->alurBelajar()->create(['id' => 'alur-belajar-'.fake()->uuid(),
            'tahap' => 'B',]);

        $rafa->savings()->create(['id' => 'saving-'.fake()->uuid(),
            'saving' => 250000,]);

        $rafa->savingApplication()->create(['id' => 'saving-application-'.fake()->uuid(),
            'category' => 'SPP',
            'status' => 'pending',]);

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
