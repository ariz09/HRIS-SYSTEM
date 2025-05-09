<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Drop the users table first if it exists
        Schema::dropIfExists('users');

        // Create the users table
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // Needed for foreign key constraints
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Safe foreign key setup
            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employee_info')
                ->nullOnDelete();

            $table->string('temp_password')->nullable();
            $table->boolean('password_changed')->default(false);
            $table->rememberToken();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Insert default system employee
        $employeeId = DB::table('employee_info')->insertGetId([
            'employee_number' => '00000',
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'gender' => '3', // Assuming '3' = non-binary
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert default admin user
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@sample.com',
            'password' => Hash::make('admin123456'),
            'employee_id' => $employeeId,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
