<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEducationsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('employee_educations');

        Schema::create('employee_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                  ->constrained('employee_info')
                  ->onDelete('cascade');
            $table->string('school_name');
            $table->string('course_taken');
            $table->string('inclusive_dates');
            $table->enum('level', ['college', 'masteral']);
            $table->enum('status', ['undergraduate', 'graduate']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_educations');
    }
}
