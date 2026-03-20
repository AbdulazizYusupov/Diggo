<?php

namespace Database\Seeders\Student;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        $directors = DB::table('directors')->select('id', 'school_id')->get()->toArray();

        if (empty($directors)) {
            $this->command->error("Directorlar topilmadi! Avval DirectorSeeder ni ishga tushiring.");
            return;
        }

        $students = [
            ['name' => 'Abbos',   'surname' => 'Hikmatov',     'm_name' => 'Olimovich',      'gender' => 'male',   'birth' => '2010-05-12', 'parent' => 'Olim Hikmatov',      'parent_phone' => '+998901234501', 'address' => 'Navoiy ko\'chasi 14-uy',     'city' => 'Toshkent',    'region' => 'Toshkent shahri'],
            ['name' => 'Zuhra',   'surname' => 'Abdukarimova', 'm_name' => 'Farhodovna',      'gender' => 'female', 'birth' => '2011-03-20', 'parent' => 'Farhod Abdukarimov', 'parent_phone' => '+998901234502', 'address' => 'Mustaqillik ko\'chasi 7-uy', 'city' => 'Samarqand',   'region' => 'Samarqand viloyati'],
            ['name' => 'Javohir', 'surname' => 'Sultonov',     'm_name' => 'Bekzodovich',     'gender' => 'male',   'birth' => '2009-11-15', 'parent' => 'Bekzod Sultonov',    'parent_phone' => '+998901234503', 'address' => 'Bog\'ishamol ko\'chasi 3-uy','city' => 'Namangan',    'region' => 'Namangan viloyati'],
            ['name' => 'Madina',  'surname' => 'Ismoilova',    'm_name' => 'Jasurbekovna',    'gender' => 'female', 'birth' => '2012-01-10', 'parent' => 'Jasurbek Ismoilov',  'parent_phone' => '+998901234504', 'address' => 'Amir Temur ko\'chasi 21-uy', 'city' => 'Qarshi',      'region' => 'Qashqadaryo viloyati'],
            ['name' => 'Doston',  'surname' => 'Hamidov',      'm_name' => 'Rustamovich',     'gender' => 'male',   'birth' => '2010-08-05', 'parent' => 'Rustam Hamidov',     'parent_phone' => '+998901234505', 'address' => 'Yoshlar ko\'chasi 5-uy',     'city' => 'Andijon',     'region' => 'Andijon viloyati'],
            ['name' => 'Lola',    'surname' => 'Azimova',      'm_name' => 'Shavkatovna',     'gender' => 'female', 'birth' => '2011-07-22', 'parent' => 'Shavkat Azimov',     'parent_phone' => '+998901234506', 'address' => 'Sharq ko\'chasi 9-uy',       'city' => 'Buxoro',      'region' => 'Buxoro viloyati'],
            ['name' => 'Sardor',  'surname' => 'Xalilov',      'm_name' => 'Iskandarovich',   'gender' => 'male',   'birth' => '2009-09-30', 'parent' => 'Iskandar Xalilov',   'parent_phone' => '+998901234507', 'address' => 'Furqat ko\'chasi 33-uy',     'city' => 'Farg\'ona',   'region' => 'Farg\'ona viloyati'],
            ['name' => 'Guli',    'surname' => 'Rahmonova',    'm_name' => 'Dilshodovna',     'gender' => 'female', 'birth' => '2010-12-14', 'parent' => 'Dilshod Rahmonov',   'parent_phone' => '+998901234508', 'address' => 'Guliston ko\'chasi 18-uy',   'city' => 'Guliston',    'region' => 'Sirdaryo viloyati'],
            ['name' => 'Ibrohim', 'surname' => 'Zokirov',      'm_name' => 'Akmalovich',      'gender' => 'male',   'birth' => '2011-04-18', 'parent' => 'Akmal Zokirov',      'parent_phone' => '+998901234509', 'address' => 'Ibn Sino ko\'chasi 6-uy',    'city' => 'Urganch',     'region' => 'Xorazm viloyati'],
            ['name' => 'Shahlo',  'surname' => 'Mirzayeva',    'm_name' => 'G\'olibovna',     'gender' => 'female', 'birth' => '2012-06-25', 'parent' => 'G\'olib Mirzayev',   'parent_phone' => '+998901234510', 'address' => 'Istiqlol ko\'chasi 12-uy',   'city' => 'Nukus',       'region' => 'Qoraqalpog\'iston'],
            ['name' => 'Bobur',   'surname' => 'Tursunboyev',  'm_name' => 'Murodovich',      'gender' => 'male',   'birth' => '2009-02-11', 'parent' => 'Murod Tursunboyev',  'parent_phone' => '+998901234511', 'address' => 'Navro\'z ko\'chasi 2-uy',    'city' => 'Termiz',      'region' => 'Surxondaryo viloyati'],
            ['name' => 'Nozima',  'surname' => 'Qodirova',     'm_name' => 'Anvarovna',       'gender' => 'female', 'birth' => '2010-10-02', 'parent' => 'Anvar Qodirov',      'parent_phone' => '+998901234512', 'address' => 'Yangi hayot ko\'chasi 8-uy', 'city' => 'Jizzax',      'region' => 'Jizzax viloyati'],
            ['name' => 'Sanjar',  'surname' => 'Baxtiyorov',   'm_name' => 'Umidovich',       'gender' => 'male',   'birth' => '2011-09-09', 'parent' => 'Umid Baxtiyorov',    'parent_phone' => '+998901234513', 'address' => 'Mustaqillik ko\'chasi 44-uy','city' => 'Navoiy',      'region' => 'Navoiy viloyati'],
            ['name' => 'Rayhona', 'surname' => 'Soliyeva',     'm_name' => 'Xayrulloyevna',   'gender' => 'female', 'birth' => '2012-05-30', 'parent' => 'Xayrullo Soliyev',   'parent_phone' => '+998901234514', 'address' => 'Universitet ko\'chasi 1-uy', 'city' => 'Qarshi',      'region' => 'Qashqadaryo viloyati'],
            ['name' => 'Islom',   'surname' => 'Karimov',      'm_name' => 'A\'zamovich',     'gender' => 'male',   'birth' => '2009-12-12', 'parent' => 'A\'zam Karimov',     'parent_phone' => '+998901234515', 'address' => 'Shaxrisabz ko\'chasi 17-uy', 'city' => 'Shahrisabz',  'region' => 'Qashqadaryo viloyati'],
            ['name' => 'Ziyoda',  'surname' => 'Umarova',      'm_name' => 'Baxtiyorovna',    'gender' => 'female', 'birth' => '2010-01-25', 'parent' => 'Baxtiyor Umarov',    'parent_phone' => '+998901234516', 'address' => 'Beruniy ko\'chasi 29-uy',    'city' => 'Samarqand',   'region' => 'Samarqand viloyati'],
            ['name' => 'Oybek',   'surname' => 'Eshonov',      'm_name' => 'Bahodirovich',    'gender' => 'male',   'birth' => '2011-11-11', 'parent' => 'Bahodir Eshonov',    'parent_phone' => '+998901234517', 'address' => 'Tinchlik ko\'chasi 11-uy',   'city' => 'Andijon',     'region' => 'Andijon viloyati'],
            ['name' => 'Mohira',  'surname' => 'Saidova',      'm_name' => 'Sherzodovna',     'gender' => 'female', 'birth' => '2012-04-04', 'parent' => 'Sherzod Saidov',     'parent_phone' => '+998901234518', 'address' => 'Olmazor ko\'chasi 55-uy',    'city' => 'Toshkent',    'region' => 'Toshkent shahri'],
            ['name' => 'Asadbek', 'surname' => 'To\'rayev',    'm_name' => 'Mansurovich',     'gender' => 'male',   'birth' => '2010-07-07', 'parent' => 'Mansur To\'rayev',   'parent_phone' => '+998901234519', 'address' => 'Ipak yo\'li ko\'chasi 3-uy', 'city' => 'Buxoro',      'region' => 'Buxoro viloyati'],
            ['name' => 'Malika',  'surname' => 'Yusupova',     'm_name' => 'Elyorovna',       'gender' => 'female', 'birth' => '2009-08-18', 'parent' => 'Elyor Yusupov',      'parent_phone' => '+998901234520', 'address' => 'Hamza ko\'chasi 22-uy',      'city' => 'Namangan',    'region' => 'Namangan viloyati'],
        ];

        foreach ($students as $data) {
            $randomDirector = $directors[array_rand($directors)];

            $user = User::create([
                'name'        => $data['name'],
                'surname'     => $data['surname'],
                'middle_name' => $data['m_name'],
                'phone'       => '9' . rand(10000000, 99999999),
                'birth_date'  => $data['birth'],
                'gender'      => $data['gender'],
                'address'     => $data['address'],
                'city'        => $data['city'],
                'region'      => $data['region'],
                'email'       => strtolower($data['name']) . rand(100, 999) . '@student.uz',
                'password'    => Hash::make('student123'),
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);

            $user->assignRole($studentRole);

            DB::table('students')->insert([
                'user_id'         => $user->id,
                'director_id'     => $randomDirector->id,
                'school_id'       => $randomDirector->school_id,
                'enrollment_date' => $now->format('Y-m-d'),
                'coins'           => 0,
                'balance'         => 0,
                'parent_name'     => $data['parent'],
                'parent_phone'    => $data['parent_phone'],
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }

        $this->command->info("20 ta student yaratildi va 'student' roli biriktirildi!");
    }
}