<?php

namespace Database\Seeders\Director;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DirectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Avval mavjud maktablarni olamiz
        $schoolIds = DB::table('schools')->pluck('id')->toArray();
        $role = Role::firstOrCreate(['name' => 'director']);

        if (empty($schoolIds)) {
            $this->command->error("Maktablar topilmadi! Avval SchoolSeeder ni ishga tushiring.");
            return;
        }

        $directorsData = [
            [
                'user' => [
                    'name' => 'Farhod',
                    'surname' => 'Umarov',
                    'middle_name' => 'Zokirovich',
                    'phone' => '901110011',
                    'birth_date' => '1975-04-10',
                    'gender' => 'male',
                    'city' => 'Toshkent',
                    'region' => 'Toshkent shahri',
                    'email' => 'director1@school.uz',
                    'password' => Hash::make('director123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'commission' => 10.00
            ],
            [
                'user' => [
                    'name' => 'Nigora',
                    'surname' => 'Usmanova',
                    'middle_name' => 'Anvarovna',
                    'phone' => '932223344',
                    'birth_date' => '1980-08-22',
                    'gender' => 'female',
                    'city' => 'Samarqand',
                    'region' => 'Samarqand viloyati',
                    'email' => 'director2@school.uz',
                    'password' => Hash::make('director123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'commission' => 8.50
            ],
            [
                'user' => [
                    'name' => 'Dilshod',
                    'surname' => 'Karimov',
                    'middle_name' => 'Abduvohidovich',
                    'phone' => '945556677',
                    'birth_date' => '1978-12-05',
                    'gender' => 'male',
                    'city' => 'Buxoro',
                    'region' => 'Buxoro viloyati',
                    'email' => 'director3@school.uz',
                    'password' => Hash::make('director123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'commission' => 5.00
            ],
            [
                'user' => [
                    'name' => 'Shohista',
                    'surname' => 'Rahimova',
                    'middle_name' => 'Murodovna',
                    'phone' => '971112233',
                    'birth_date' => '1983-01-30',
                    'gender' => 'female',
                    'city' => 'Namangan',
                    'region' => 'Namangan viloyati',
                    'email' => 'director4@school.uz',
                    'password' => Hash::make('director123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'commission' => 12.00
            ],
            [
                'user' => [
                    'name' => 'Azamat',
                    'surname' => 'Tursunov',
                    'middle_name' => 'Po\'latovich',
                    'phone' => '998887766',
                    'birth_date' => '1970-06-15',
                    'gender' => 'male',
                    'city' => 'Qarshi',
                    'region' => 'Qashqadaryo viloyati',
                    'email' => 'director5@school.uz',
                    'password' => Hash::make('director123'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                'commission' => 7.00
            ],
        ];

        foreach ($directorsData as $index => $data) {
            // 1. Userni yaratamiz (Model orqali)
            $user = User::create($data['user']);
            // 2. Spatie orqali rol biriktiramiz
            $user->assignRole($role);

            // 3. Director jadvaliga ulaymiz
            if (isset($schoolIds[$index])) {
                DB::table('directors')->insert([
                    'user_id' => $user->id,
                    'school_id' => $schoolIds[$index],
                    'commission_percentage' => $data['commission'],
                    'balance' =>0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
