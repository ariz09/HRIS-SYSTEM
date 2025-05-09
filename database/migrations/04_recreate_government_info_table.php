<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop foreign keys from other tables that reference employee_info
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                try {
                    $table->dropForeign(['employee_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist yet
                }
            });
        }

        if (Schema::hasTable('personal_info')) {
            Schema::table('personal_info', function (Blueprint $table) {
                try {
                    $table->dropForeign(['employee_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist yet
                }
            });
        }

        if (Schema::hasTable('government_info')) {
            Schema::table('government_info', function (Blueprint $table) {
                try {
                    $table->dropForeign(['employee_info_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist yet
                }
            });

            Schema::dropIfExists('government_info');
        }

        // DO NOT drop employee_info here unless explicitly recreating it in this file.
        // If you must, ensure this is safe.
        // Schema::dropIfExists('employee_info');

        // Recreate government_info table
        Schema::create('government_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_info_id')
                  ->constrained('employee_info')
                  ->onDelete('cascade'); // ensures dependent records are deleted
            $table->string('sss_number')->nullable();
            $table->string('pag_ibig_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('tin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_info');
    }
};
