<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesAndPermissionsTables extends Migration
{
    public function up()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop roles table if it exists before creating
        Schema::dropIfExists('roles');

        // Create roles table if it doesn't exist
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'employee'],
            ['name' => 'recruiter'],
            ['name' => 'timekeeper'],
            ['name' => 'payroll officer'],
            ['name' => 'supervisor'],
            ['name' => 'manager'],
            ['name' => 'admin']
        ]);

        // Drop permissions table if it exists before creating
        Schema::dropIfExists('permissions');
        
        // Create permissions table if it doesn't exist
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Drop permission_role pivot table if it exists before creating
        Schema::dropIfExists('permission_role');
        
        // Create permission_role pivot table if it doesn't exist
        Schema::create('permission_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->timestamps();
        });

        // Drop role_user pivot table if it exists before creating
        Schema::dropIfExists('role_user');
        
        // Create role_user pivot table if it doesn't exist
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down()
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop all the tables in reverse order
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
