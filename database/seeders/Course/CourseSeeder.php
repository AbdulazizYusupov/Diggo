<?php

namespace Database\Seeders\Course;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $courses = [
            [
                'title' => 'Ingliz tili (General English)',
                'level' => 'beginner',
                'price' => 550000.00,
                'duration' => 6,
                'prefix' => 'ENG-BEG'
            ],
            [
                'title' => 'IELTS Preparation',
                'level' => 'advanced',
                'price' => 800000.00,
                'duration' => 4,
                'prefix' => 'ENG-IELTS'
            ],
            [
                'title' => 'Frontend Web Dasturlash',
                'level' => 'intermediate',
                'price' => 1200000.00,
                'duration' => 6,
                'prefix' => 'IT-FRONT'
            ],
            [
                'title' => 'Backend (PHP & Laravel)',
                'level' => 'advanced',
                'price' => 1500000.00,
                'duration' => 7,
                'prefix' => 'IT-BACK'
            ],
            [
                'title' => 'Rus tili (Razgovorniy)',
                'level' => 'intermediate',
                'price' => 450000.00,
                'duration' => 3,
                'prefix' => 'RUS-INT'
            ],
            [
                'title' => 'Grafik Dizayn (Adobe Suite)',
                'level' => 'beginner',
                'price' => 900000.00,
                'duration' => 4,
                'prefix' => 'IT-DESIGN'
            ],
            [
                'title' => 'Matematika (DTM to‘plam)',
                'level' => 'beginner',
                'price' => 400000.00,
                'duration' => 9,
                'prefix' => 'MATH-BEG'
            ],
            [
                'title' => 'Mental Arifmetika',
                'level' => 'beginner',
                'price' => 350000.00,
                'duration' => 3,
                'prefix' => 'MATH-MENT'
            ],
            [
                'title' => 'Python for Kids',
                'level' => 'beginner',
                'price' => 700000.00,
                'duration' => 5,
                'prefix' => 'IT-PY-KIDS'
            ],
            [
                'title' => 'Arab tili (Tajvid)',
                'level' => 'beginner',
                'price' => 450000.00,
                'duration' => 4,
                'prefix' => 'ARB-BEG'
            ],
        ];

        foreach ($courses as $index => $course) {
            DB::table('cours')->insert([
                'title'           => $course['title'],
                'code'            => $course['prefix'] . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                'description'     => $course['title'] . " kursi bo'yicha chuqurlashtirilgan darslar. Daraja: " . $course['level'],
                'price'           => $course['price'],
                'duration_months' => $course['duration'],
                'level'           => $course['level'],
                'is_active'       => true,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        $this->command->info("10 ta kurs muvaffaqiyatli qo'shildi!");
    }
}
