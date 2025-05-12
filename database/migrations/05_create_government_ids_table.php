<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('government_ids');

        Schema::create('government_ids', function (Blueprint $table) {
            $table->id();

            // Foreign key to employment_infos based on employee_number
            $table->string('employee_number');
            $table->foreign('employee_number')->references('employee_number')->on('employment_infos')->onDelete('cascade');

            // Government ID fields
            $table->string('sss_number')->nullable();
            $table->string('pag_ibig_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('tin')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_ids');
    }
};
