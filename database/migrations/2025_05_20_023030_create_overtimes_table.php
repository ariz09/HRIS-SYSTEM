<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->text('reason');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->foreign('employee_number')
                  ->references('employee_number')
                  ->on('employment_infos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('overtimes');
    }
};