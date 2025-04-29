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
            $table->foreignId('agency_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('cdm_level_id')->constrained()->onDelete('cascade');
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->foreignId('employment_status_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name_suffix')->nullable();
            $table->string('alias')->nullable();
            $table->date('hiring_date');
            $table->date('last_day')->nullable();
            $table->decimal('basic_pay', 10, 2)->default(0);
            $table->decimal('rata', 10, 2)->default(0);
            $table->decimal('comm_allowance', 10, 2)->default(0);
            $table->decimal('transpo_allowance', 10, 2)->default(0);
            $table->string('sss_number')->nullable();
            $table->string('pag_ibig_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('tin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}

