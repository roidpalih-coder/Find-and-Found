<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'KTP / Identitas',           'slug' => 'ktp-identitas',   'icon' => 'id-card',    'is_priority_document' => true],
            ['name' => 'SIM',                        'slug' => 'sim',             'icon' => 'car',        'is_priority_document' => true],
            ['name' => 'Paspor',                     'slug' => 'paspor',          'icon' => 'book',       'is_priority_document' => true],
            ['name' => 'Kartu Pelajar / Mahasiswa',  'slug' => 'kartu-pelajar',   'icon' => 'graduation', 'is_priority_document' => true],
            ['name' => 'HP / Smartphone',            'slug' => 'hp-smartphone',   'icon' => 'smartphone', 'is_priority_document' => false],
            ['name' => 'Dompet',                     'slug' => 'dompet',          'icon' => 'wallet',     'is_priority_document' => false],
            ['name' => 'Kunci',                      'slug' => 'kunci',           'icon' => 'key',        'is_priority_document' => false],
            ['name' => 'Tas / Ransel',               'slug' => 'tas-ransel',      'icon' => 'backpack',   'is_priority_document' => false],
            ['name' => 'Uang',                       'slug' => 'uang',            'icon' => 'banknote',   'is_priority_document' => false],
            ['name' => 'Lainnya',                    'slug' => 'lainnya',         'icon' => 'package',    'is_priority_document' => false],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
