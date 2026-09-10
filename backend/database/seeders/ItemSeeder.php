<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'user')->first() ?? User::first();
        $admin = User::where('role', 'admin')->first();

        $items = [
            [
                'user_id' => $user->id,
                'category_id' => 1, // KTP
                'type' => 'lost',
                'title' => 'Kehilangan KTP atas nama Muhammad Alqaus',
                'description' => 'Dompet beserta KTP terjatuh saat berkendara di sekitar Alun-alun Pati menuju Stadion Joyokusumo.',
                'secret_details' => 'NIK berakhiran 0003, ada stiker kecil di balik KTP.',
                'incident_date' => now()->subDays(2),
                'location_name' => 'Alun-Alun Simpang Lima Pati',
                'district' => 'Pati Kota',
                'latitude' => -6.7533,
                'longitude' => 111.0379,
                'primary_photo_url' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=600&auto=format&fit=crop&q=80',
                'reward_offered' => 'Rp 100.000',
                'status' => 'open',
            ],
            [
                'user_id' => $admin->id,
                'category_id' => 5, // HP
                'type' => 'found',
                'title' => 'Ditemukan iPhone 11 Warna Hitam di Masjid Agung',
                'description' => 'Ditemukan di area pelataran parkir Masjid Agung Pati setelah sholat Ashar.',
                'secret_details' => 'Case bening berstiker kucing, wallpaper foto pemandangan gunung.',
                'incident_date' => now()->subDays(1),
                'location_name' => 'Masjid Agung Baitunnur Pati',
                'district' => 'Pati Kota',
                'latitude' => -6.7537,
                'longitude' => 111.0385,
                'primary_photo_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=600&auto=format&fit=crop&q=80',
                'reward_offered' => null,
                'status' => 'open',
            ],
            [
                'user_id' => $user->id,
                'category_id' => 7, // Kunci
                'type' => 'found',
                'title' => 'Ditemukan Kunci Motor Honda Vario + Gantungan Boneka',
                'description' => 'Tergantung di dekat kasir minimarket daerah Juwana.',
                'secret_details' => 'Ada 3 anak kunci tambahan dan gantungan rajut warna kuning.',
                'incident_date' => now()->subHours(8),
                'location_name' => 'Minimarket Depan Pasar Juwana',
                'district' => 'Juwana',
                'latitude' => -6.7167,
                'longitude' => 111.1500,
                'primary_photo_url' => 'https://images.unsplash.com/photo-1582139329536-e7284fece509?w=600&auto=format&fit=crop&q=80',
                'reward_offered' => null,
                'status' => 'open',
            ],
            [
                'user_id' => $admin->id,
                'category_id' => 2, // SIM
                'type' => 'lost',
                'title' => 'Kehilangan SIM C di Sekitar RSUD Soewondo',
                'description' => 'Tercecer saat parkir di halaman parkir belakang RSUD Soewondo Pati.',
                'secret_details' => 'Masa berlaku hingga 2028, penerbitan Polres Pati.',
                'incident_date' => now()->subDays(3),
                'location_name' => 'RSUD RAA Soewondo Pati',
                'district' => 'Pati Kota',
                'latitude' => -6.7450,
                'longitude' => 111.0333,
                'primary_photo_url' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=600&auto=format&fit=crop&q=80',
                'reward_offered' => 'Ucapan Terimakasih & Uang Bensin',
                'status' => 'open',
            ],
        ];

        foreach ($items as $data) {
            Item::create($data);
        }
    }
}
