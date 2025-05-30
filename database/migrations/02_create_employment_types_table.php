<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Drop the employment_types table if it exists
        Schema::dropIfExists('employment_types');

        // Create the employment_types table
        Schema::create('employment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Added unique constraint
            $table->boolean('is_active')->default(true); // Added active status flag
            $table->timestamps();
        });

        // Insert default employment types using insertOrIgnore
        DB::table('employment_types')->insertOrIgnore([
            [
                'name' => 'Regular',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Probationary',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Contractual',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Project-Based',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Part-Time',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Seasonal',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Intern',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Consultant',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        // Drop the employment_types table if it exists
        Schema::dropIfExists('employment_types');
    }
};
