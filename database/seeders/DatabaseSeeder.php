<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Cours;
use App\Models\Director;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\GroupTeacher;
use App\Models\Payment;
use App\Models\School;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\Activity\ActivitySeeder;
use Database\Seeders\Course\CourseSeeder;
use Database\Seeders\Director\DirectorSeeder;
use Database\Seeders\School\SchoolSeeder;
use Database\Seeders\Staff\StaffSeeder;
use Database\Seeders\Student\StudentSeeder;
use Database\Seeders\Teacher\TeacherSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'surname' => fake()->name(),
        //     'phone' => fake()->phoneNumber(),
        // ]);

        $this->call([
            RolePermissionSeeder::class,
            TeacherSeeder::class,
            SchoolSeeder::class,
            DirectorSeeder::class,
            StudentSeeder::class,
            StaffSeeder::class,
            CourseSeeder::class,
            ActivitySeeder::class
        ]);
    }
}
