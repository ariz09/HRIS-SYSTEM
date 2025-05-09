<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Ensure referenced tables exist first
        if (!Schema::hasTable('users') || !Schema::hasTable('employee_info')) {
            throw new \Exception("Required tables 'users' and/or 'employee_info' do not exist. Run those migrations first.");
        }

        // Drop the table first to reset
        Schema::dropIfExists('personal_info');

        Schema::create('personal_info', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('employee_id')->nullable();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name_suffix')->nullable();
            $table->string('preferred_name')->nullable();
            $table->string('gender');
            $table->date('birthday')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('civil_status')->nullable();

            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('employee_id')
                ->references('id')
                ->on('employee_info')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('personal_info');
    }
};
