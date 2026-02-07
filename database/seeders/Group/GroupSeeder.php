<?php

namespace Database\Seeders\Group;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        // Kurslarni ID bo'yicha olamiz (CourseSeeder ishlagan bo'lishi kerak)
        $courseIds = DB::table('cours')->pluck('id')->toArray();

        if (empty($courseIds)) {
            $this->command->error("Kurslar topilmadi! Avval CourseSeeder-ni ishlating.");
            return;
        }

        $groups = [
            [
                'name' => 'Backend - B1',
                'cours_id' => $courseIds[3] ?? $courseIds[0], // Backend kursi yoki birinchisi
                'start_time' => '14:00:00',
                'end_time' => '16:00:00',
                'days' => ['monday', 'wednesday', 'friday'],
                'start_date' => $now->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(7)->format('Y-m-d'),
            ],
            [
                'name' => 'IELTS Intensive',
                'cours_id' => $courseIds[1] ?? $courseIds[0],
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'days' => ['tuesday', 'thursday', 'saturday'],
                'start_date' => $now->copy()->addDays(5)->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(4)->format('Y-m-d'),
            ],
            [
                'name' => 'Frontend Pro',
                'cours_id' => $courseIds[2] ?? $courseIds[0],
                'start_time' => '11:00:00',
                'end_time' => '13:00:00',
                'days' => ['monday', 'wednesday', 'friday'],
                'start_date' => $now->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(6)->format('Y-m-d'),
            ],
            [
                'name' => 'Graphic Design Night',
                'cours_id' => $courseIds[5] ?? $courseIds[0],
                'start_time' => '18:30:00',
                'end_time' => '20:30:00',
                'days' => ['tuesday', 'thursday', 'saturday'],
                'start_date' => $now->copy()->subDays(10)->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(4)->format('Y-m-d'),
            ],
            [
                'name' => 'Arabic - Beginner',
                'cours_id' => $courseIds[9] ?? $courseIds[0],
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
                'start_date' => $now->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(4)->format('Y-m-d'),
            ],
            [
                'name' => 'Python Kids - Weekend',
                'cours_id' => $courseIds[8] ?? $courseIds[0],
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'days' => ['saturday', 'sunday'],
                'start_date' => $now->copy()->addDays(15)->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(5)->format('Y-m-d'),
            ],
        ];

        foreach ($groups as $group) {
            DB::table('groups')->insert([
                'name'         => $group['name'],
                'cours_id'     => $group['cours_id'],
                'start_time'   => $group['start_time'],
                'end_time'     => $group['end_time'],
                'days_of_week' => json_encode($group['days']),
                'capacity'     => 15,
                'start_date'   => $group['start_date'],
                'end_date'     => $group['end_date'],
                'is_active'    => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        $this->command->info("6 ta maxsus guruh muvaffaqiyatli yaratildi!");
    }
    
}
