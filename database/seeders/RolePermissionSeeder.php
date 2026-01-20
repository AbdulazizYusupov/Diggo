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
        $adminUser = User::create([
            'name' => 'System',
            'surname' => 'Admin',
            'phone' => '998881156101',
            'email' => 'admin@gmail.com',
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
        $adminUser->assignRole('admin');
    }
}
