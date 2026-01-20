<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser1 = User::create([
            'name' => 'Muhammadnabi',
            'surname' => 'Xoliqulov',
            'phone' => '998881156101',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $adminUser2 = User::create([
            'name' => 'Abdulaziz',
            'surname' => 'Yusupov',
            'phone' => '998937450307',
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $adminUser3 = User::create([
            'name' => 'System',
            'surname' => 'Admin',
            'phone' => '998990000000',
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $roles = [
            'admin',
            'director',
            'staff',
            'teacher',
            'student'
        ];

        foreach ($roles as $roleName) {
            Role::findOrCreate($roleName);
        }
        $adminUser1->assignRole('admin');
        $adminUser2->assignRole('admin');
        $adminUser3->assignRole('admin');
    }
}
