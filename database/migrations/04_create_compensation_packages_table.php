<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('compensation_packages');

        Schema::create('compensation_packages', function (Blueprint $table) {
            $table->id();

            // Foreign key to employment_infos based on employee_number
            $table->string('employee_number');
            $table->foreign('employee_number')->references('employee_number')->on('employment_infos')->onDelete('cascade');

            // Compensation details
            $table->decimal('basic_pay', 15, 2);
            $table->decimal('rata', 15, 2)->nullable();
            $table->decimal('comm_allowance', 15, 2)->nullable();
            $table->decimal('transpo_allowance', 15, 2)->nullable();
            $table->decimal('parking_toll_allowance', 15, 2)->nullable();
            $table->decimal('clothing_allowance', 15, 2)->nullable();
            $table->string('atm_account_number')->nullable();
            $table->string('bank_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compensation_packages');
    }
};
