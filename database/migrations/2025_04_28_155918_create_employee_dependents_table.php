<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDependentsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('employee_dependents');

        Schema::create('employee_dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                  ->constrained('employee_info')
                  ->onDelete('cascade');
            $table->enum('dependent_type', ['spouse', 'child','parent']);
            $table->string('name');
            $table->date('birthdate');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_dependents');
    }
}
