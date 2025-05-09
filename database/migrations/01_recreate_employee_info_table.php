<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('employee_info');
        
        Schema::create('employee_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Just the column, no foreign key
            $table->string('employee_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['1', '2', '3']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_info');
    }
};
