<?php

namespace Database\Seeders;

use App\Models\EventData;
use Illuminate\Database\Seeder;

class EventDataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['peserta' => '< 50', 'budget' => 'Rendah', 'speaker' => 'Biasa', 'topik' => 'Trending', 'hasil' => 'Gagal'],
            ['peserta' => '< 50', 'budget' => 'Tinggi', 'speaker' => 'Expert', 'topik' => 'Trending', 'hasil' => 'Sukses'],
            ['peserta' => '≥ 50', 'budget' => 'Tinggi', 'speaker' => 'Expert', 'topik' => 'Trending', 'hasil' => 'Sukses'],
            ['peserta' => '< 50', 'budget' => 'Rendah', 'speaker' => 'Biasa', 'topik' => 'Niche', 'hasil' => 'Gagal'],
            ['peserta' => '≥ 50', 'budget' => 'Tinggi', 'speaker' => 'Expert', 'topik' => 'Niche', 'hasil' => 'Sukses'],
            ['peserta' => '< 50', 'budget' => 'Tinggi', 'speaker' => 'Biasa', 'topik' => 'Trending', 'hasil' => 'Gagal'],
            ['peserta' => '≥ 50', 'budget' => 'Rendah', 'speaker' => 'Expert', 'topik' => 'Trending', 'hasil' => 'Sukses'],
            ['peserta' => '≥ 50', 'budget' => 'Tinggi', 'speaker' => 'Expert', 'topik' => 'Trending', 'hasil' => 'Sukses'],
            ['peserta' => '< 50', 'budget' => 'Rendah', 'speaker' => 'Expert', 'topik' => 'Niche', 'hasil' => 'Gagal'],
            ['peserta' => '≥ 50', 'budget' => 'Tinggi', 'speaker' => 'Expert', 'topik' => 'Niche', 'hasil' => 'Sukses'],
        ];

        foreach ($data as $item) {
            EventData::create($item);
        }
    }
}