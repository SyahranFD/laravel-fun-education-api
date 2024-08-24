<?php

namespace Database\Seeders;

use App\Models\AlurBelajar;
use App\Models\Saving;
use App\Models\SavingApplication;
use App\Models\ShiftMasuk;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        function randomColor() {
            $r = mt_rand(0, 225);
            $g = mt_rand(0, 225);
            $b = mt_rand(0, 225);

            return sprintf('%02X%02X%02X', $r, $g, $b);
        }

        $background_color_random = randomColor();
        $full_name_admin = Config::get('admin.nama');
        $nickname_admin = explode(' ', $full_name_admin)[0];
        $profile_picture_admin = 'https://ui-avatars.com/api/?name=' . urlencode($full_name_admin) . '&color=FFFFFF&background=' . $background_color_random . '&size=128';

        User::create(['id' => 'user-'.fake()->uuid(), 'full_name' => $full_name_admin, 'nickname' => $nickname_admin, 'email' => 'funeducationapp@gmail.com', 'birth' => 'Batam, 10 Agustus 1980', 'address' => 'Griya Batu Aji Ari Blok G1, No 06', 'password' => Hash::make(Config::get('admin.password')), 'gender' => 'Perempuan', 'profile_picture' => $profile_picture_admin, 'is_verified' => true, 'is_verified_email' => true,
            'role' => 'admin',]);

        $shifts = ['08.00 - 10.00', '10.00 - 11.30', '11.30 - 13.00', '13.00 - 14.00', '14.00 - 15.00',];

        foreach ($shifts as $shift) {
            ShiftMasuk::create(['shift_masuk' => $shift]);
        }

        for ($i = 1; $i <= 50; $i++) {
            do {
                $full_name = implode(' ', array_slice(explode(' ', fake()->name), 0, 2));
                $nickname = explode(' ', $full_name)[0];
            } while (User::where('nickname', $nickname)->exists());
            $backgroundColor = randomColor();
            $profile_picture_user = 'https://ui-avatars.com/api/?name=' . urlencode($full_name) . '&color=FFFFFF&background=' . $backgroundColor . '&size=128';
            $cities = ['Semarang', 'Jakarta', 'Surabaya', 'Bandung', 'Yogyakarta'];
            $city = $cities[array_rand($cities)];
            $birth = $city . ', ' . fake()->dateTimeBetween('-10 years', '-6 years')->format('j F Y');
            $email = strtolower($nickname) . '@gmail.com';

            $is_verified = false;
            if ($i <= 40) {
                $is_verified = true;
            }

            $is_graduated = false;
            if ($i <= 10) {
                $is_graduated = true;
            }

            $user = User::create([
                'id' => 'user-' . fake()->uuid(),
                'full_name' => $full_name,
                'nickname' => $nickname,
                'email' => $email,
                'birth' => $birth,
                'address' => fake()->address,
                'shift' => $shifts[($i - 1) % count($shifts)],
                'password' => Hash::make('pass'),
                'gender' => fake()->randomElement(['Laki-Laki', 'Perempuan']),
                'profile_picture' => $profile_picture_user,
                'role' => 'student',
                'is_verified' => $is_verified,
                'is_verified_email' => true,
                'is_graduated' => $is_graduated,
            ]);

            $randomBase = rand(20, 50);
            $tabungan = $randomBase * 10000;
            $tabunganFinal = $tabungan - 100000;
            $transaksi = $tabungan / 2;

            Saving::create(['id' => 'saving-' . fake()->uuid(), 'user_id' => $user->id, 'saving' => $tabunganFinal,]);
            SavingApplication::create(['id' => 'saving-application-' . fake()->uuid(), 'user_id' => $user->id, 'category' => fake()->randomElement(['SPP', 'Kegiatan']), 'status' => 'Pending',]);
            Transaction::create(['id' => 'transaction-' . fake()->uuid(), 'user_id' => $user->id, 'category' => 'income', 'amount' => $transaksi, 'created_at' => fake()->dateTimeBetween('-1 month', 'now'),]);
            Transaction::create(['id' => 'transaction-' . fake()->uuid(), 'user_id' => $user->id, 'category' => 'income', 'amount' => $transaksi, 'created_at' => fake()->dateTimeBetween('-1 month', 'now'),]);
            Transaction::create(['id' => 'transaction-' . fake()->uuid(), 'user_id' => $user->id, 'category' => 'outcome', 'amount' => 100000, 'created_at' => fake()->dateTimeBetween('-1 month', 'now'), 'desc' => 'Untuk Bayar SPP',]);
            AlurBelajar::create(['id' => 'alur-belajar-' . fake()->uuid(), 'user_id' => $user->id, 'tahap' => chr(rand(65, 67)),]);
        }
    }
}
