<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_emergency_contacts', function (Blueprint $table) {
            // Drop existing columns
            $table->dropColumn(['mobile_number', 'present_address']);

            // Add new column
            $table->string('phone')->nullable()->after('relationship');

            // Modify existing columns to be nullable
            $table->string('name')->nullable()->change();
            $table->string('relationship')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_emergency_contacts', function (Blueprint $table) {
            // Revert changes
            $table->dropColumn('phone');
            $table->string('mobile_number');
            $table->string('present_address');
            $table->string('name')->change();
            $table->string('relationship')->change();
        });
    }
};
