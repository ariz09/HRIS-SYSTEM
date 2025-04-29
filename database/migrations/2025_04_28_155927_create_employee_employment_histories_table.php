<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_employee_employment_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEmploymentHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('employee_employment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('company');
            $table->string('position_held');
            $table->string('employment_period');
            $table->decimal('last_salary', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_employment_histories');
    }
}
