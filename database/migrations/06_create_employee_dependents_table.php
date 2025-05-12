<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('employee_dependents');

        Schema::create('employee_dependents', function (Blueprint $table) {
            $table->id();

            // Foreign key to employment_infos based on employee_number
            $table->string('employee_number');
            $table->foreign('employee_number')->references('employee_number')->on('employment_infos')->onDelete('cascade');

            // Dependent information
            $table->enum('dependent_type', ['spouse', 'child', 'parent', 'other']);
            $table->string('full_name');
            $table->date('birthdate');
            $table->string('contact_number')->nullable(); // optional contact number

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_dependents');
    }
};
