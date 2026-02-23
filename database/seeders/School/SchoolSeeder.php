<?php

namespace Database\Seeders\School;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTime = Carbon::now();

        $schools = [
            [
                'name' => 'Toshkent Prezident Maktabi',
                'code' => 'PMS-001',
                'address' => 'Amir Temur ko‘chasi, 15-uy',
                'city' => 'Toshkent',
                'region' => 'Toshkent shahri',
                'phone' => '712001122',
                'description' => 'Iqtidorli bolalar uchun ixtisoslashtirilgan ta’lim muassasasi.',
                'is_active' => true,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
            [
                'name' => 'Al-Xorazmiy nomidagi IT maktabi',
                'code' => 'ITS-002',
                'address' => 'Mirzo Ulug‘bek tumani, Do‘rmon yo‘li',
                'city' => 'Toshkent',
                'region' => 'Toshkent shahri',
                'phone' => '712345678',
                'description' => 'Axborot texnologiyalariga ixtisoslashgan maktab.',
                'is_active' => true,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
            [
                'name' => 'Samarqand 1-sonli umumta’lim maktabi',
                'code' => 'SCH-SAM01',
                'address' => 'Registon ko‘chasi, 45-uy',
                'city' => 'Samarqand',
                'region' => 'Samarqand viloyati',
                'phone' => '662310011',
                'description' => 'Viloyatdagi eng qadimiy va nufuzli maktablardan biri.',
                'is_active' => true,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
            [
                'name' => 'Buxoro "Qorako‘l" litsey-maktabi',
                'code' => 'QRK-007',
                'address' => 'Mustaqillik shoh ko‘chasi, 12-uy',
                'city' => 'Qorako‘l',
                'region' => 'Buxoro viloyati',
                'phone' => '652234455',
                'description' => 'Matematika fani bo‘yicha respublika darajasidagi kuchli maktab.',
                'is_active' => true,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
            [
                'name' => 'Namangan "Ziyo" xususiy maktabi',
                'code' => 'ZIYO-NAM',
                'address' => 'Uychi ko‘chasi, 100-uy',
                'city' => 'Namangan',
                'region' => 'Namangan viloyati',
                'phone' => '692223344',
                'description' => 'Chet tillarini chuqurlashtirib o‘rgatishga yo‘naltirilgan xususiy maktab.',
                'is_active' => true,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
        ];

        DB::table('schools')->insert($schools);
    }
}
