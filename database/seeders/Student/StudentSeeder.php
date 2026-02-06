<?php

namespace Database\Seeders\Student;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $schoolIds = DB::table('schools')->pluck('id')->toArray();
        $directorIds = DB::table('directors')->pluck('id')->toArray();
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        $students = [
            ['name' => 'Abbos', 'surname' => 'Hikmatov', 'm_name' => 'Olimovich', 'gender' => 'male', 'birth' => '2010-05-12', 'parent' => 'Olim Hikmatov'],
            ['name' => 'Zuhra', 'surname' => 'Abdukarimova', 'm_name' => 'Farhodovna', 'gender' => 'female', 'birth' => '2011-03-20', 'parent' => 'Farhod Abdukarimov'],
            ['name' => 'Javohir', 'surname' => 'Sultonov', 'm_name' => 'Bekzodovich', 'gender' => 'male', 'birth' => '2009-11-15', 'parent' => 'Bekzod Sultonov'],
            ['name' => 'Madina', 'surname' => 'Ismoilova', 'm_name' => 'Jasurbekovna', 'gender' => 'female', 'birth' => '2012-01-10', 'parent' => 'Jasurbek Ismoilov'],
            ['name' => 'Doston', 'surname' => 'Hamidov', 'm_name' => 'Rustamovich', 'gender' => 'male', 'birth' => '2010-08-05', 'parent' => 'Rustam Hamidov'],
            ['name' => 'Lola', 'surname' => 'Azimova', 'm_name' => 'Shavkatovna', 'gender' => 'female', 'birth' => '2011-07-22', 'parent' => 'Shavkat Azimov'],
            ['name' => 'Sardor', 'surname' => 'Xalilov', 'm_name' => 'Iskandarovich', 'gender' => 'male', 'birth' => '2009-09-30', 'parent' => 'Iskandar Xalilov'],
            ['name' => 'Guli', 'surname' => 'Rahmonova', 'm_name' => 'Dilshodovna', 'gender' => 'female', 'birth' => '2010-12-14', 'parent' => 'Dilshod Rahmonov'],
            ['name' => 'Ibrohim', 'surname' => 'Zokirov', 'm_name' => 'Akmalovich', 'gender' => 'male', 'birth' => '2011-04-18', 'parent' => 'Akmal Zokirov'],
            ['name' => 'Shahlo', 'surname' => 'Mirzayeva', 'm_name' => 'G‘olibovna', 'gender' => 'female', 'birth' => '2012-06-25', 'parent' => 'G‘olib Mirzayev'],
            ['name' => 'Bobur', 'surname' => 'Tursunboyev', 'm_name' => 'Murodovich', 'gender' => 'male', 'birth' => '2009-02-11', 'parent' => 'Murod Tursunboyev'],
            ['name' => 'Nozima', 'surname' => 'Qodirova', 'm_name' => 'Anvarovna', 'gender' => 'female', 'birth' => '2010-10-02', 'parent' => 'Anvar Qodirov'],
            ['name' => 'Sanjar', 'surname' => 'Baxtiyorov', 'm_name' => 'Umidovich', 'gender' => 'male', 'birth' => '2011-09-09', 'parent' => 'Umid Baxtiyorov'],
            ['name' => 'Rayhona', 'surname' => 'Soliyeva', 'm_name' => 'Xayrulloyevna', 'gender' => 'female', 'birth' => '2012-05-30', 'parent' => 'Xayrullo Soliyev'],
            ['name' => 'Islom', 'surname' => 'Karimov', 'm_name' => 'A’zamovich', 'gender' => 'male', 'birth' => '2009-12-12', 'parent' => 'A’zam Karimov'],
            ['name' => 'Ziyoda', 'surname' => 'Umarova', 'm_name' => 'Baxtiyorovna', 'gender' => 'female', 'birth' => '2010-01-25', 'parent' => 'Baxtiyor Umarov'],
            ['name' => 'Oybek', 'surname' => 'Eshonov', 'm_name' => 'Bahodirovich', 'gender' => 'male', 'birth' => '2011-11-11', 'parent' => 'Bahodir Eshonov'],
            ['name' => 'Mohira', 'surname' => 'Saidova', 'm_name' => 'Sherzodovna', 'gender' => 'female', 'birth' => '2012-04-04', 'parent' => 'Sherzod Saidov'],
            ['name' => 'Asadbek', 'surname' => 'To‘rayev', 'm_name' => 'Mansurovich', 'gender' => 'male', 'birth' => '2010-07-07', 'parent' => 'Mansur To‘rayev'],
            ['name' => 'Malika', 'surname' => 'Yusupova', 'm_name' => 'Elyorovna', 'gender' => 'female', 'birth' => '2009-08-18', 'parent' => 'Elyor Yusupov'],
        ];

        foreach ($students as $data) {
            // 1. Userni Model orqali yaratamiz
            $user = User::create([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'middle_name' => $data['m_name'],
                'phone' => '+9989' . rand(10000000, 99999999),
                'birth_date' => $data['birth'],
                'gender' => $data['gender'],
                'city' => 'Toshkent',
                'region' => 'Toshkent shahri',
                'email' => strtolower($data['name']) . rand(100, 999) . '@student.uz',
                'password' => Hash::make('student123'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // 2. Role assign qilamiz
            $user->assignRole($studentRole);

            // 3. Student jadvaliga ma'lumot qo'shamiz
            DB::table('students')->insert([
                'user_id' => $user->id,
                'school_id' => !empty($schoolIds) ? $schoolIds[array_rand($schoolIds)] : null,
                'director_id' => !empty($directorIds) ? $directorIds[array_rand($directorIds)] : null,
                'enrollment_date' => $now->format('Y-m-d'),
                'coins' => rand(10, 50),
                'balance' => rand(100000, 1000000),
                'parent_name' => $data['parent'],
                'parent_phone' => '+9989' . rand(10000000, 99999999),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $this->command->info("20 ta student yaratildi va 'student' roli biriktirildi!");
    }
}
