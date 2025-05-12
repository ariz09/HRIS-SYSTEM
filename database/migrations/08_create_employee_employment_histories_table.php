<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('employee_employment_histories');

        Schema::create('employee_employment_histories', function (Blueprint $table) {
            $table->id();

            // Foreign key to employment_infos based on employee_number
            $table->string('employee_number');
            $table->foreign('employee_number')->references('employee_number')->on('employment_infos')->onDelete('cascade');

            // Employment history details
            $table->string('job_title');
            $table->text('job_description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_employment_histories');
    }
};
