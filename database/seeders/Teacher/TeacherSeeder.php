<?php

namespace Database\Seeders\Teacher;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);

        $teachersData = [
            [
                'user' => [
                    'name' => 'Alijon',
                    'surname' => 'Valiyev',
                    'middle_name' => 'Akramovich',
                    'phone' => '+998901234567',
                    'birth_date' => '1985-05-15',
                    'gender' => 'male',
                    'address' => 'Chilonzor 19-kvartal',
                    'city' => 'Toshkent',
                    'region' => 'Toshkent shahri',
                    'email' => 'alijon.v@example.com',
                    'password' => Hash::make('password123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'teacher' => [
                    'salary_type' => 'fixed',
                    'salary_amount' => 5000000.00,
                    'percent_per_student' => null,
                ]
            ],
            [
                'user' => [
                    'name' => 'Gulnora',
                    'surname' => 'Sodiqova',
                    'middle_name' => 'Baxtiyorovna',
                    'phone' => '+998935554433',
                    'birth_date' => '1990-02-20',
                    'gender' => 'female',
                    'address' => 'Amir Temur ko‘chasi',
                    'city' => 'Samarqand',
                    'region' => 'Samarqand viloyati',
                    'email' => 'gulnora.s@example.com',
                    'password' => Hash::make('password123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'teacher' => [
                    'salary_type' => 'percent',
                    'salary_amount' => 0,
                    'percent_per_student' => 30.00,
                ]
            ],
            [
                'user' => [
                    'name' => 'Jahongir',
                    'surname' => 'Rustamov',
                    'middle_name' => 'Qobilovich',
                    'phone' => '+998941112233',
                    'birth_date' => '1988-11-10',
                    'gender' => 'male',
                    'address' => 'Mustaqillik ko‘chasi',
                    'city' => 'Buxoro',
                    'region' => 'Buxoro viloyati',
                    'email' => 'jahongir.r@example.com',
                    'password' => Hash::make('password123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'teacher' => [
                    'salary_type' => 'hourly',
                    'salary_amount' => 45000.00, // soatiga
                    'percent_per_student' => null,
                ]
            ],
            [
                'user' => [
                    'name' => 'Malika',
                    'surname' => 'Axmedova',
                    'middle_name' => 'Otabekovna',
                    'phone' => '+998977778899',
                    'birth_date' => '1995-08-25',
                    'gender' => 'female',
                    'address' => 'Bobur shoh ko‘chasi',
                    'city' => 'Andijon',
                    'region' => 'Andijon viloyati',
                    'email' => 'malika.a@example.com',
                    'password' => Hash::make('password123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'teacher' => [
                    'salary_type' => 'per_group',
                    'salary_amount' => 1200000.00, // har bir guruh uchun
                    'percent_per_student' => null,
                ]
            ],
            [
                'user' => [
                    'name' => 'Sardor',
                    'surname' => 'Islomov',
                    'middle_name' => 'Umarovich',
                    'phone' => '+998909001020',
                    'birth_date' => '1982-03-12',
                    'gender' => 'male',
                    'address' => 'Islom Karimov ko‘chasi',
                    'city' => 'Namangan',
                    'region' => 'Namangan viloyati',
                    'email' => 'sardor.i@example.com',
                    'password' => Hash::make('password123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'teacher' => [
                    'salary_type' => 'fixed',
                    'salary_amount' => 7000000.00,
                    'percent_per_student' => null,
                ]
            ],
        ];

        foreach ($teachersData as $data) {
            // 2. Userni Model orqali yaratamiz (timestamps avtomatik qo'shiladi)
            $user = User::create(array_merge($data['user'], [
                'created_at' => $now,
                'updated_at' => $now,
            ]));

            // 3. Teacher rolini biriktiramiz
            $user->assignRole($teacherRole);

            // 4. Olingan User ID bilan Teacher jadvalini to'ldiramiz
            DB::table('teachers')->insert(array_merge($data['teacher'], [
                'user_id' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        $this->command->info("Teacherlar muvaffaqiyatli yaratildi va 'teacher' roli biriktirildi!");
    }
}
