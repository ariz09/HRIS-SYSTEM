<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('employee_number')->unique();
            $table->foreignId('agency_id')->constrained('agencies');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('cdm_level_id')->constrained('cdm_levels');
            $table->foreignId('gender_id')->constrained('genders');
            $table->foreignId('employment_type_id')->constrained('employment_types');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('name_suffix')->nullable();
            $table->string('alias')->nullable();
            $table->foreignId('position_id')->constrained('positions');
            $table->date('hiring_date');
            $table->date('last_day')->nullable();
            $table->foreignId('employment_status_id')->constrained('employment_statuses');
            $table->string('tenure')->nullable();
            $table->decimal('basic_pay', 12, 2);
            $table->decimal('rata', 12, 2)->default(0);
            $table->decimal('comm_allowance', 12, 2)->default(0);
            $table->decimal('transpo_allowance', 12, 2)->default(0);
            $table->decimal('parking_toll_allowance', 12, 2)->default(0);
            $table->decimal('clothing_allowance', 12, 2)->default(0);
            $table->decimal('total_package', 12, 2);
            $table->string('atm_account_number')->nullable();
            $table->date('birthday');
            $table->string('sss_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('pag_ibig_number')->nullable();
            $table->string('tin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
