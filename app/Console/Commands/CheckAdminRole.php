<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class CheckAdminRole extends Command
{
    protected $signature = 'admin:check-role';
    protected $description = 'Check and fix admin role assignment';

    public function handle()
    {
        $this->info('Checking admin role...');

        // Get or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $this->info("Admin role ID: {$adminRole->id}");

        // Find admin user
        $adminUser = User::where('email', 'admin@sample.com')->first();

        if (!$adminUser) {
            $this->error('Admin user not found!');
            return;
        }

        $this->info("Admin user ID: {$adminUser->id}");

        // Check if admin has role
        if (!$adminUser->roles()->where('name', 'admin')->exists()) {
            $adminUser->roles()->attach($adminRole->id);
            $this->info('Admin role assigned to admin user.');
        } else {
            $this->info('Admin already has admin role.');
        }

        // Show current roles
        $roles = $adminUser->roles()->pluck('name');
        $this->info('Current roles: ' . implode(', ', $roles->toArray()));
    }
}
