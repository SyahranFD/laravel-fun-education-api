<?php

namespace Database\Seeders;

use App\Models\Tugas;
use App\Models\TugasImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = ['08.00 - 10.00', '10.00 - 11.30', '11.30 - 13.00', '13.00 - 14.00', '14.00 - 15.00',];

        $tasks = [
            [ 'category' => 'Dikte & Menulis', 'title' => 'Menulis 5 benda yang sering dilihat oleh ananda', 'description' => 'Ananda diminta untuk menulis 5 benda yang sering dilihat oleh ananda di rumah. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Tersedia', 'image' => 'https://storyblok-image.ef.com/unsafe/1500x750/filters:focal(960x375:961x376):quality(70)/f/78828/0ceea5d3e6/ef-id-blog-top-banner-benda-wajib-di-kantor.jpg' ],
            [ 'category' => 'Kreasi', 'title' => 'Mewarnai gambar', 'description' => 'Ananda diminta untuk mewarnai gambar yang sudah diberikan. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Tersedia', 'image' => 'https://i.pinimg.com/736x/77/16/a1/7716a1ac49ce270899d5b0ae61914453.jpg' ],
            [ 'category' => 'Membaca', 'title' => 'Membaca kartu baju sampai cabe', 'description' => 'Ananda diminta untuk membaca kartu baju sampai cabe. Setelah selesai, videokan anak saat membaca lalu kumpulkan.', 'status' => 'Tersedia', 'image' => 'https://cantol.wordpress.com/wp-content/uploads/2009/04/kartu1.png' ],
            [ 'category' => 'Berhitung', 'title' => 'Perhatikan soal berikut', 'description' => 'Ananda diminta untuk mengerjakan soal berikut. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Tersedia', 'image' => 'https://cdn-2.tstatic.net/bangka/foto/bank/images/soal-tk-1.jpg' ],
            [ 'category' => 'Dikte & Menulis', 'title' => 'Menulis Huruf A-J', 'description' => 'Ananda diminta untuk menulis 5 benda yang sering dilihat oleh ananda di rumah. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Ditutup', 'image' => 'https://asset-a.grid.id/crop/0x0:0x0/x/photo/2023/09/14/huruf-kapitaljpg-20230914090831.jpg' ],
            [ 'category' => 'Dikte & Menulis', 'title' => 'Menulis Huruf K-T,', 'description' => 'Ananda diminta untuk menulis huruf K-T. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Diarsipkan', 'image' => 'https://asset-a.grid.id/crop/0x0:0x0/x/photo/2023/09/14/huruf-kapitaljpg-20230914090831.jpg' ],
            [ 'category' => 'Dikte & Menulis', 'title' => 'Menulis Huruf U-Z', 'description' => 'Ananda diminta untuk menulis huruf U-Z. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Ditutup', 'image' => 'https://asset-a.grid.id/crop/0x0:0x0/x/photo/2023/09/14/huruf-kapitaljpg-20230914090831.jpg' ],
            [ 'category' => 'Dikte & Menulis', 'title' => 'Menulis Cerita Pendek', 'description' => 'Ananda diminta untuk menulis cerita pendek tentang keluarga. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Ditutup', 'image' => 'https://i.pinimg.com/736x/77/16/a1/7716a1ac49ce270899d5b0ae61914453.jpg' ],
            [ 'category' => 'Kreasi', 'title' => 'Membuat Kerajinan Tangan', 'description' => 'Ananda diminta untuk membuat kerajinan tangan dari bahan-bahan yang ada di rumah. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Ditutup', 'image' => 'https://i.pinimg.com/736x/77/16/a1/7716a1ac49ce270899d5b0ae61914453.jpg' ],
            [ 'category' => 'Membaca', 'title' => 'Membaca Buku Cerita', 'description' => 'Ananda diminta untuk membaca buku cerita yang diberikan. Setelah selesai, videokan anak saat membaca lalu kumpulkan.', 'status' => 'Ditutup', 'image' => 'https://i.pinimg.com/736x/77/16/a1/7716a1ac49ce270899d5b0ae61914453.jpg' ],
            [ 'category' => 'Berhitung', 'title' => 'Menghitung Jumlah Buah', 'description' => 'Ananda diminta untuk menghitung jumlah buah yang ada di gambar. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Ditutup', 'image' => 'https://i.pinimg.com/736x/77/16/a1/7716a1ac49ce270899d5b0ae61914453.jpg' ],
            [ 'category' => 'Dikte & Menulis', 'title' => 'Menulis Nama-nama Binatang', 'description' => 'Ananda diminta untuk menulis nama-nama binatang yang ada di kebun binatang. Setelah selesai, foto hasil tugas anak lalu kumpulkan.', 'status' => 'Ditutup', 'image' => 'https://i.pinimg.com/736x/77/16/a1/7716a1ac49ce270899d5b0ae61914453.jpg' ]
        ];

        foreach ($tasks as $index => $task) {
            $createdAt = (new \DateTime())->modify('-' . rand(1, 60) . ' days');
            $deadline = (new \DateTime())->modify('+' . rand(1, 12) . ' days');

            $task['created_at'] = $createdAt->format('Y-m-d');
            $task['deadline'] = $deadline->format('Y-m-d');

            foreach ($shifts as $shift) {
                $tugas = Tugas::create(['id' => 'tugas-'.fake()->uuid(), 'shift' => $shift, 'category' => $task['category'], 'title' => $task['title'], 'description' => $task['description'], 'status' => $task['status'], 'created_at' => $task['created_at'], 'deadline' => $task['deadline'],]);
                TugasImage::create(['id' => 'tugas-image-'.fake()->uuid(), 'tugas_id' => $tugas->id, 'image' => $task['image'],]);
            }
        }
    }
}
