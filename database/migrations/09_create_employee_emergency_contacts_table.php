<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('employee_emergency_contacts');

        Schema::create('employee_emergency_contacts', function (Blueprint $table) {
            $table->id();

            // Foreign key to employment_infos based on employee_number
            $table->string('employee_number');
            $table->foreign('employee_number')->references('employee_number')->on('employment_infos')->onDelete('cascade');

            // Emergency contact details
            $table->string('fullname');
            $table->enum('relationship', ['spouse', 'child', 'parent', 'other']);
            $table->string('contact_number');
            $table->text('address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_emergency_contacts');
    }
};
