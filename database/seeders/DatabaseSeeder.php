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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'surname' => fake()->name(),
            'phone' => fake()->phoneNumber(),
        ]);

        $adminUser = User::create([
            'name' => 'System',
            'surname' => 'Admin',
            'phone' => '998900000001',
            'password' => Hash::make('password'),
        ]);

        $directorUser = User::create([
            'name' => 'Ali',
            'surname' => 'Director',
            'phone' => '998900000002',
            'password' => Hash::make('password'),
        ]);

        $teacherUser = User::create([
            'name' => 'Vali',
            'surname' => 'Teacher',
            'phone' => '998900000003',
            'password' => Hash::make('password'),
        ]);

        $staffUser = User::create([
            'name' => 'Hasan',
            'surname' => 'Staff',
            'phone' => '998900000004',
            'password' => Hash::make('password'),
        ]);

        $studentUser = User::create([
            'name' => 'Student',
            'surname' => 'One',
            'phone' => '998900000005',
            'password' => Hash::make('password'),
        ]);

        /**
         * ======================
         * SCHOOL
         * ======================
         */

        $school = School::create([
            'name' => 'Demo Academy',
            'code' => 'DEMO-001',
            'city' => 'Tashkent',
            'region' => 'Yunusobod',
            'phone' => '998711111111',
        ]);

        /**
         * ======================
         * DIRECTOR
         * ======================
         */

        $director = Director::create([
            'user_id' => $directorUser->id,
            'school_id' => $school->id,
            'commission_percentage' => 5,
        ]);


        /**
         * ======================
         * STAFF
         * ======================
         */

        Staff::create([
            'user_id' => $staffUser->id,
            'salary_type' => 'fixed',
            'salary_amount' => 3000000,
        ]);

        /**
         * ======================
         * TEACHER
         * ======================
         */

        $teacher = Teacher::create([
            'user_id' => $teacherUser->id,
            'salary_type' => 'percent',
            'percent_per_student' => 5,
        ]);


        $course = Cours::create([
            'title' => 'English Beginner',
            'code' => 'ENG-BEG-01',
            'price' => 500000,
            'duration_months' => 3,
            'level' => 'beginner',
        ]);

        $group = Group::create([
            'name' => 'ENG-BEG-A',
            'cours_id' => $course->id,
            'capacity' => 20,
            'days_of_week' => json_encode(['monday', 'wednesday', 'friday']),
            'start_date' => now(),
        ]);

     

        $group->teachers()->attach($teacherUser->id, [
            'is_primary' => true,
            'assigned_at' => now(),
        ]);

        /**
         * ======================
         * STUDENT
         * ======================
         */

        $student = Student::create([
            'user_id' => $studentUser->id,
            'school_id' => $school->id,
            'director_id' => $director->id,
            'enrollment_date' => now(),
        ]);

      

        $group->students()->attach($student->id, [
            'joined_at' => now(),
            'status' => 'active',
        ]);
    }
}
