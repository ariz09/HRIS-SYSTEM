<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@example.com',
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ],
            [
                'name' => 'Alice Williams',
                'email' => 'alice.williams@example.com',
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ],
            [
                'name' => 'kyle',
                'email' => 'clffrdkyl@gmail.com',
                'password' => Hash::make('password123'),
                'is_active' => 1,
            ],
        ];

        foreach ($admins as $admin) {
            DB::table('admins')->updateOrInsert(
                ['email' => $admin['email']],
                $admin
            );
        }
    }
}
