<?php

namespace Database\Seeders\Staff;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Staff rolini topamiz yoki yaratamiz
        $staffRole = Role::firstOrCreate(['name' => 'staff']);

        $staffData = [
            ['name' => 'Farhod', 'surname' => 'Eshonov', 'm_name' => 'G‘ayratovich', 'email' => 'farhod.e@staff.uz', 'salary' => 4500000],
            ['name' => 'Dilnoza', 'surname' => 'Qosimova', 'm_name' => 'Shavkatovna', 'email' => 'dilnoza.q@staff.uz', 'salary' => 3800000],
            ['name' => 'Jasur', 'surname' => 'Abdullayev', 'm_name' => 'Rustamovich', 'email' => 'jasur.a@staff.uz', 'salary' => 5200000],
            ['name' => 'Zaynab', 'surname' => 'Mo‘minova', 'm_name' => 'Ilyosovna', 'email' => 'zaynab.m@staff.uz', 'salary' => 4000000],
            ['name' => 'Sirojiddin', 'surname' => 'Xolov', 'm_name' => 'Ulug‘bekovich', 'email' => 'siroj.x@staff.uz', 'salary' => 6000000],
            ['name' => 'Shohista', 'surname' => 'Karimova', 'm_name' => 'Anvarovna', 'email' => 'shohista.k@staff.uz', 'salary' => 3500000],
            ['name' => 'Elyor', 'surname' => 'Toshpo‘latov', 'm_name' => 'Baxtiyorovich', 'email' => 'elyor.t@staff.uz', 'salary' => 4800000],
            ['name' => 'Nigora', 'surname' => 'Sultonova', 'm_name' => 'Jamshidovna', 'email' => 'nigora.s@staff.uz', 'salary' => 4200000],
            ['name' => 'Otabek', 'surname' => 'Yusupov', 'm_name' => 'Akmalovich', 'email' => 'otabek.y@staff.uz', 'salary' => 5500000],
            ['name' => 'Gulzoda', 'surname' => 'Odilova', 'm_name' => 'Farruxovna', 'email' => 'gulzoda.o@staff.uz', 'salary' => 3900000],
        ];

        foreach ($staffData as $data) {
            // 2. User yaratish
            $user = User::create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'middle_name' => $data['m_name'],
                'phone' => '+9989' . rand(10000000, 99999999),
                'birth_date' => Carbon::now()->subYears(rand(20, 45))->format('Y-m-d'), // 20-45 yosh oralig'i
                'gender' => (rand(0, 1) ? 'male' : 'female'),
                'city' => 'Toshkent',
                'region' => 'Toshkent shahri',
                'email' => $data['email'],
                'password' => Hash::make('staff123'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // 3. Staff rolini biriktirish
            $user->assignRole($staffRole);

            // 4. Staff jadvaliga ma'lumot qo'shish
            DB::table('staff')->insert([
                'user_id' => $user->id,
                'salary_type' => 'fixed',
                'salary_amount' => $data['salary'],
                'percent_per_student' => null,
                'percent_per_payment' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info("10 ta xodim (Staff) yaratildi va rollar biriktirildi!");
    }
}
