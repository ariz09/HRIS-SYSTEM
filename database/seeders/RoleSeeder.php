<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'employee', 'guard_name' => 'web']);
        Role::create(['name' => 'recruiter', 'guard_name' => 'web']);
        Role::create(['name' => 'timekeeper', 'guard_name' => 'web']);
        Role::create(['name' => 'payroll officer', 'guard_name' => 'web']);
        Role::create(['name' => 'supervisor', 'guard_name' => 'web']);
        Role::create(['name' => 'manager', 'guard_name' => 'web']);
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
    }
}
