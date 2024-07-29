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
use App\Models\TugasCategory;
use App\Models\Tugas;
use App\Models\TugasImage;
use App\Models\TugasUser;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            AlurBelajar::create(['id' => 'alur-belajar-' . fake()->uuid(), 'user_id' => $user->id, 'tahap' => chr(rand(65, 67)),]);
        }

        CatatanDarurat::create(['id' => 'catatan-darurat-'.fake()->uuid(), 'catatan' => 'Diharapkan ananda membawa payung/jas hujan karena kondisi mendung.',]);

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

        AlurBelajar::create(['id' => 'alur-belajar-'.fake()->uuid(), 'user_id' => $rafa->id, 'tahap' => 'B',]);
        Saving::create(['id' => 'saving-'.fake()->uuid(), 'user_id' => $rafa->id, 'saving' => 250000,]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'amount' => 15000, 'category' => 'income', 'created_at' => '2024-07-19',]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'category' => 'income', 'amount' => 25000, 'created_at' => '2024-07-18',]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'category' => 'income', 'amount' => 15000, 'created_at' => '2024-07-17',]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'category' => 'outcome', 'amount' => 75000, 'desc' => 'Untuk Bayar SPP', 'created_at' => '2024-07-17',]);
        Transaction::create(['id' => 'transaction-'.fake()->uuid(), 'user_id' => $rafa->id, 'category' => 'outcome', 'amount' => 140000, 'desc' => 'Untuk membayar biaya outbound', 'created_at' => '2024-07-16',]);

        $album = Album::create(['id' => 'album-'.fake()->uuid(),
            'name' => 'Museum Batam Raja Ali Haji',
            'desc' => 'Kumpulan foto di museum raja ali haji',
            'cover' => 'https://disbudpar.batam.go.id/wp-content/uploads/sites/22/2023/07/WhatsApp-Image-2023-07-28-at-11.34.14-1024x682@2x.jpeg']);

        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipPFRtcGA5Ix9TJl2APPrZyUrcCWB7UjOSlDdB7Z=s1360-w1360-h1020', 'title' => 'Foto dari Kejauhan', 'description' => 'Foto dari Kejauhan Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipP_YwJA1mAC-yuqju3z4w5mXOXB-u2uzmrsHXIV=s1360-w1360-h1020', 'title' => 'Sisi Depan', 'description' => 'Sisi Depan Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipMuYixrQrLC8olvTgHpfQDdDrNKWiZ2eo43n55H=s1360-w1360-h1020', 'title' => 'Aula Museum', 'description' => 'Aula Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipMMac77s4KNdAP47FcsXvjzuVTto-leyzN1G6yB=s1360-w1360-h1020', 'title' => 'Kapal', 'description' => 'Kapal Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipPiqY3hj3xCRHSNLAixR4ikjZ-2Vr-46muaNrw4=s1360-w1360-h1020', 'title' => 'Baju Museum', 'description' => 'Baju di Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipOy5KS2EsifJtHLP1Db1YpIAyS-97BfQgjdJegc=s1360-w1360-h1020', 'title' => 'Peta Museum', 'description' => 'Peta di Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipNqasP4C7KFnojiJXvNBTPo-9y2zUv3OrP5xVSm=s1360-w1360-h1020', 'title' => 'Sejarah Kerajaan', 'description' => 'Tulisan Sejarah Kerajaan di Museum Raja Ali Haji',]);

        $tasks = [
            [ 'category' => 'Dikte & Menulis', 'title' => 'Menulis 5 benda yang sering dilihat oleh ananda', 'description' => 'Ananda diminta untuk menulis 5 benda yang sering dilihat oleh ananda di rumah. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Tersedia', 'image' => 'https://storyblok-image.ef.com/unsafe/1500x750/filters:focal(960x375:961x376):quality(70)/f/78828/0ceea5d3e6/ef-id-blog-top-banner-benda-wajib-di-kantor.jpg' ],
            [ 'category' => 'Kreasi', 'title' => 'Mewarnai gambar', 'description' => 'Ananda diminta untuk mewarnai gambar yang sudah diberikan. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Tersedia', 'image' => 'https://i.pinimg.com/736x/77/16/a1/7716a1ac49ce270899d5b0ae61914453.jpg' ],
            [ 'category' => 'Membaca', 'title' => 'Membaca kartu baju sampai cabe', 'description' => 'Ananda diminta untuk membaca kartu baju sampai cabe. Setelah selesai, videokan anak saat membaca lalu kumpulkan.', 'status' => 'Tersedia', 'image' => 'https://cantol.wordpress.com/wp-content/uploads/2009/04/kartu1.png' ],
            [ 'category' => 'Berhitung', 'title' => 'Perhatikan soal berikut', 'description' => 'Ananda diminta untuk mengerjakan soal berikut. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Tersedia', 'image' => 'https://cdn-2.tstatic.net/bangka/foto/bank/images/soal-tk-1.jpg' ],
            [ 'category' => 'Dikte & Menulis', 'title' => 'Menulis Huruf A-J', 'description' => 'Ananda diminta untuk menulis 5 benda yang sering dilihat oleh ananda di rumah. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Ditutup', 'image' => 'https://asset-a.grid.id/crop/0x0:0x0/x/photo/2023/09/14/huruf-kapitaljpg-20230914090831.jpg' ],
            [ 'category' => 'Dikte & Menulis', 'title' => 'Menulis Huruf K-T', 'description' => 'Ananda diminta untuk menulis huruf K-T. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Diarsipkan', 'image' => 'https://asset-a.grid.id/crop/0x0:0x0/x/photo/2023/09/14/huruf-kapitaljpg-20230914090831.jpg' ]
        ];

        foreach ($tasks as $index => $task) {
            $createdAt = (new \DateTime())->modify('-' . rand(1, 7) . ' days');
            $deadline = (new \DateTime())->modify('+' . rand(1, 7) . ' days');

            $task['created_at'] = $createdAt->format('Y-m-d');
            $task['deadline'] = $deadline->format('Y-m-d');

            foreach ($shifts as $shift) {
                $tugas = Tugas::create(['id' => 'tugas-'.fake()->uuid(), 'shift' => $shift, 'category' => $task['category'], 'title' => $task['title'], 'description' => $task['description'], 'status' => $task['status'], 'created_at' => $task['created_at'], 'deadline' => $task['deadline'],]);
                TugasImage::create(['id' => 'tugas-image-'.fake()->uuid(), 'tugas_id' => $tugas->id, 'image' => $task['image'],]);
            }
        }

        $tugasList = Tugas::where('status', 'Tersedia')->get();

        foreach (User::all() as $user) {
            if ($user->nickname === 'Syahran') { continue; }
            if ($user->role === 'admin') { continue; }
            foreach ($tugasList as $tugas) {
                $tugasUser = TugasUser::create(['id' => 'tugas-user-'.fake()->uuid(), 'tugas_id' => $tugas->id, 'user_id' => $user->id, 'note' => 'Ini ya bu tugasnya',]);
                TugasUserImage::create(['id' => 'tugas-user-image-'.fake()->uuid(), 'tugas_user_id' => $tugasUser->id, 'image' => 'https://i.ytimg.com/vi/b2q4Pc8f7jM/hqdefault.jpg']);
            }
        }

        $tugasRafaList = Tugas::where('status', 'Tersedia')->where('shift', $rafa->shift)->orderBy('created_at', 'desc')->get();
        $rafaTugasUser1 = TugasUser::create(['status' => 'Diperiksa', 'id' => 'tugas-user-'.fake()->uuid(), 'tugas_id' => $tugasRafaList[2]->id, 'user_id' => $rafa->id, 'note' => 'Ini ya bu tugasnya',]);
        $rafaTugasUser2 = TugasUser::create(['status' => 'Selesai', 'id' => 'tugas-user-'.fake()->uuid(), 'tugas_id' => $tugasRafaList[3]->id, 'user_id' => $rafa->id, 'note' => 'Ini ya bu tugasnya',]);
        TugasUserImage::create(['id' => 'tugas-user-image-'.fake()->uuid(), 'tugas_user_id' => $rafaTugasUser1->id, 'image' => 'https://i.ytimg.com/vi/b2q4Pc8f7jM/hqdefault.jpg']);
        TugasUserImage::create(['id' => 'tugas-user-image-'.fake()->uuid(), 'tugas_user_id' => $rafaTugasUser2->id, 'image' => 'https://i.ytimg.com/vi/b2q4Pc8f7jM/hqdefault.jpg']);
    }
}
