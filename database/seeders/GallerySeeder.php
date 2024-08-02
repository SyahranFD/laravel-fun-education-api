<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Gallery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $album = Album::create(['id' => 'album-'.fake()->uuid(), 'name' => 'Museum Batam Raja Ali Haji', 'desc' => 'Kumpulan foto di museum raja ali haji', 'cover' => 'https://disbudpar.batam.go.id/wp-content/uploads/sites/22/2023/07/WhatsApp-Image-2023-07-28-at-11.34.14-1024x682@2x.jpeg']);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipPFRtcGA5Ix9TJl2APPrZyUrcCWB7UjOSlDdB7Z=s1360-w1360-h1020', 'title' => 'Foto dari Kejauhan', 'description' => 'Foto dari Kejauhan Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipP_YwJA1mAC-yuqju3z4w5mXOXB-u2uzmrsHXIV=s1360-w1360-h1020', 'title' => 'Sisi Depan', 'description' => 'Sisi Depan Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipMuYixrQrLC8olvTgHpfQDdDrNKWiZ2eo43n55H=s1360-w1360-h1020', 'title' => 'Aula Museum', 'description' => 'Aula Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipMMac77s4KNdAP47FcsXvjzuVTto-leyzN1G6yB=s1360-w1360-h1020', 'title' => 'Kapal', 'description' => 'Kapal Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipPiqY3hj3xCRHSNLAixR4ikjZ-2Vr-46muaNrw4=s1360-w1360-h1020', 'title' => 'Baju Museum', 'description' => 'Baju di Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipOy5KS2EsifJtHLP1Db1YpIAyS-97BfQgjdJegc=s1360-w1360-h1020', 'title' => 'Peta Museum', 'description' => 'Peta di Museum Raja Ali Haji',]);
        Gallery::create(['id' => 'gallery-'.fake()->uuid(), 'album_id' => $album->id, 'image' => 'https://lh3.googleusercontent.com/p/AF1QipNqasP4C7KFnojiJXvNBTPo-9y2zUv3OrP5xVSm=s1360-w1360-h1020', 'title' => 'Sejarah Kerajaan', 'description' => 'Tulisan Sejarah Kerajaan di Museum Raja Ali Haji',]);

    }
}
