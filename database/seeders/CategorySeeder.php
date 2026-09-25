<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Sarana dan Prasarana Kampus', 'type' => Category::TYPE_PENGADUAN, 'description' => 'Kerusakan ruang kuliah, laboratorium, furnitur, toilet, dan fasilitas kampus.', 'color' => '#2563EB'],
            ['name' => 'Kebersihan dan Sanitasi', 'type' => Category::TYPE_PENGADUAN, 'description' => 'Kebersihan gedung, pengelolaan sampah, toilet, dan sanitasi lingkungan kampus.', 'color' => '#16A34A'],
            ['name' => 'Teknologi Informasi dan Jaringan', 'type' => Category::TYPE_PENGADUAN, 'description' => 'Gangguan Wi-Fi, sistem akademik, akun mahasiswa, dan jaringan kampus.', 'color' => '#0891B2'],
            ['name' => 'Keamanan dan Ketertiban', 'type' => Category::TYPE_PENGADUAN, 'description' => 'Parkir, akses gedung, kehilangan barang, dan gangguan keamanan di lingkungan kampus.', 'color' => '#EAB308'],
            ['name' => 'Layanan Akademik dan Administrasi', 'type' => Category::TYPE_PENGADUAN, 'description' => 'Kendala KRS, surat akademik, transkrip, pembayaran, dan layanan administrasi mahasiswa.', 'color' => '#7C3AED'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [...$category, 'is_active' => true],
            );
        }
    }
}