<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEmploymentHistoriesTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('employee_employment_histories');

        Schema::create('employee_employment_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('job_title');
            $table->text('job_description');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            // Corrected foreign key to reference employee_info.id
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employee_info')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('employee_employment_histories', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        
        Schema::dropIfExists('employee_employment_histories');
    }
}