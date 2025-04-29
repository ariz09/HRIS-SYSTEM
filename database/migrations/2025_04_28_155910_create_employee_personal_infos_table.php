<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_employee_personal_infos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePersonalInfosTable extends Migration
{
    public function up()
    {
        Schema::create('employee_personal_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('birthdate');
            $table->string('birthplace');
            $table->string('civil_status');
            $table->string('gender');
            $table->string('religion');
            $table->string('mobile_number');
            $table->string('landline_number')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('present_address');
            $table->string('permanent_address');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_personal_infos');
    }
}
