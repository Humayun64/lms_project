<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoStudentSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'student@lms.com'],
            [
                'name'     => 'Demo Student',
                'password' => Hash::make('student123'),
                'role'     => 'student',
                'status'   => 'active',
            ]
        );
    }
}
