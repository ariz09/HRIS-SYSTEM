<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PersonalInfoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('personal_infos')->insert([
            [
                'user_id' => 1, // Make sure this user exists in your users table
                'first_name' => 'Clifford Kyle',
                'middle_name' => 'S.',
                'last_name' => 'Demafelis',
                'name_suffix' => null,
                'preferred_name' => 'Pogi',
                'gender' => 'Male',
                'birthday' => '1990-01-01',
                'email' => 'john.doe@example.com',
                'phone_number' => '09171234567',
                'civil_status' => 'Single',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 2,
                'first_name' => 'Jane',
                'middle_name' => 'B.',
                'last_name' => 'Smith',
                'name_suffix' => null,
                'preferred_name' => 'Janey',
                'gender' => 'Female',
                'birthday' => '1992-05-15',
                'email' => 'jane.smith@example.com',
                'phone_number' => '09181234567',
                'civil_status' => 'Married',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
