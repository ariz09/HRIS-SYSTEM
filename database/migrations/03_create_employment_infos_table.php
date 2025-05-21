<?php
// Migration for employment_infos table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employment_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('employee_number')->unique();
            $table->date('hiring_date')->nullable();

            // Make it nullable since you're using `set null`
            $table->foreignId('employment_status_id')->nullable()->constrained('employment_statuses')->onDelete('set null');
            $table->foreignId('agency_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->foreignId('cdm_level_id')->nullable()->constrained('cdm_levels')->onDelete('set null');
            $table->foreignId('position_id')->nullable()->constrained('positions')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employment_infos');
    }
};
