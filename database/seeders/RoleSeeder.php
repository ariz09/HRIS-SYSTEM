<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create admin role
        $adminRole = Role::firstOrCreate([
            'name' => 'admin'
        ]);

        // Find the default admin user
        $adminUser = User::where('email', 'admin@sample.com')->first();

        if ($adminUser) {
            // Check if the user already has the admin role
            if (!$adminUser->roles()->where('name', 'admin')->exists()) {
                // Assign admin role to the admin user
                $adminUser->roles()->attach($adminRole->id);
            }
        }
    }
}
