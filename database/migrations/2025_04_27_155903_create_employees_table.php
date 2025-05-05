<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_employees_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();           
            $table->string('employee_number')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name_suffix')->nullable();
            $table->string('alias')->nullable();
            $table->date('hiring_date')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->date('last_day')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('sss_number')->nullable();
            $table->string('pag_ibig_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('tin')->nullable();
            $table->string('atm_account_number')->nullable();
            $table->string('bank')->nullable();
            $table->string('phone_number')->nullable();     
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');

            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->unsignedBigInteger('cdm_level_id')->nullable();
            $table->foreign('cdm_level_id')->references('id')->on('cdm_levels')->onDelete('cascade');

            $table->unsignedBigInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');

            $table->unsignedBigInteger('employment_status_id')->nullable();
            $table->foreign('employment_status_id')->references('id')->on('employment_statuses')->onDelete('cascade');

            $table->decimal('basic_pay', 10, 2)->default(0)->nullable();
            $table->decimal('rata', 10, 2)->default(0)->nullable();
            $table->decimal('comm_allowance', 10, 2)->default(0)->nullable();
            $table->decimal('transpo_allowance', 10, 2)->default(0)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
