<?php

namespace Database\Seeders\Activity;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Guruhlarni olamiz (agar guruhlar seederi ishlagan bo'lsa)
        // Agar guruhlar hali yo'q bo'lsa, null bo'lib qolaveradi
        $groupIds = DB::table('groups')->pluck('id')->toArray();

        $activityTemplates = [
            [
                'name' => 'Unit 1: Grammar Test',
                'type' => 'homework',
                'description' => 'Present Simple va Continuous mavzulari bo\'yicha uy vazifasi',
                'max_coins' => 10
            ],
            [
                'name' => 'Oylik Monitoring Imtihoni',
                'type' => 'exam',
                'description' => 'O\'tilgan 4 ta unit bo\'yicha umumiy nazorat ishi',
                'max_coins' => 50
            ],
            [
                'name' => 'Fast Typing Competition',
                'type' => 'competition',
                'description' => 'Klaviatura bilan ishlash tezligi bo\'yicha ochiq musobaqa',
                'max_coins' => 100
            ],
            [
                'name' => 'Portfolio Taqudimoti',
                'type' => 'project',
                'description' => 'O\'quvchilarning o\'z loyihalarini namoyish etish darsi',
                'max_coins' => 75
            ],
            [
                'name' => 'Extra Vocabulary Quiz',
                'type' => 'other',
                'description' => 'Qo\'shimcha lug\'at boyligini tekshirish uchun qisqa test',
                'max_coins' => 15
            ],
            [
                'name' => 'Frontend Project: Landing Page',
                'type' => 'project',
                'description' => 'HTML va CSS yordamida birinchi saytni yaratish',
                'max_coins' => 120
            ],
            [
                'name' => 'Speaking Club Active Member',
                'type' => 'other',
                'description' => 'Speaking darslarida faol qatnashganlik uchun',
                'max_coins' => 20
            ],
            [
                'name' => 'Algorithm Challenge',
                'type' => 'competition',
                'description' => 'Mantiqiy masalalarni yechish musobaqasi',
                'max_coins' => 80
            ],
            [
                'name' => 'Final Exam (IELTS Simulation)',
                'type' => 'exam',
                'description' => 'Kurs yakuni bo\'yicha simulatsiya imtihoni',
                'max_coins' => 200
            ],
            [
                'name' => 'Bug Bounty Day',
                'type' => 'competition',
                'description' => 'Kod ichidagi xatolarni topish musobaqasi',
                'max_coins' => 90
            ],
        ];

        foreach ($activityTemplates as $index => $template) {
            DB::table('activities')->insert([
                'name'        => $template['name'],
                'type'        => $template['type'],
                'group_id'    => !empty($groupIds) ? $groupIds[array_rand($groupIds)] : null,
                'description' => $template['description'],
                'date'        => $now->copy()->subDays(rand(1, 30))->format('Y-m-d'), // Oxirgi 1 oy ichidagi sanalar
                'max_coins'   => $template['max_coins'],
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        $this->command->info("10 ta Activity (faolliklar) muvaffaqiyatli yaratildi!");
    }
}
