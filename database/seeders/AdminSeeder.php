<?php

namespace Database\Seeders;

use App\Http\Controllers\EmployeeController;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\EmploymentInfo;
use App\Models\CompensationPackage;
use App\Models\PersonalInfo;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // generate employee number
        $employee_number = EmployeeController::generateEmployeeNumber();

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        PersonalInfo::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'preferred_name' => $user->name,
        ]);

        EmploymentInfo::create([
            'user_id' => $user->id,
            'employee_number' => $employee_number,
            'hiring_date' => now()->toDateString(),
        ]);

        CompensationPackage::create([
            'employee_number' => $employee_number
        ]);

        $user->assignRole('admin');
    }
}
