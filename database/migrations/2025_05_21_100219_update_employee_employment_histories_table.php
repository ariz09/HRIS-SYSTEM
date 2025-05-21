<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmployeeEmploymentHistoriesTable extends Migration
{
    public function up()
    {
        Schema::table('employee_employment_histories', function (Blueprint $table) {
            // Rename job_description to company_address
            $table->renameColumn('job_description', 'company_address');

            // Add new required company_name column
            $table->string('company_name')->after('job_title');
        });
    }

    public function down()
    {
        Schema::table('employee_employment_histories', function (Blueprint $table) {
            // Revert column name
            $table->renameColumn('company_address', 'job_description');

            // Drop company_name column
            $table->dropColumn('company_name');
        });
    }
}
