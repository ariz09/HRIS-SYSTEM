<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\EmploymentStatus;

class InsertAdminUserIntoEmployeesTable extends Migration
{
    public function up()
    {
        // Check if 'Active' status exists, if not, insert it
        $activeStatus = EmploymentStatus::firstOrCreate(
            ['name' => 'Active'], // Column to check
            ['name' => 'Active']   // Data to insert if it does not exist
        );

        // Insert admin user
        DB::table('employees')->insert([
            'employee_number' => '99999',
            'agency_id' => 1, // Assuming the agency_id you want to use
            'department_id' => 1, // Assuming the department_id you want to use
            'cdm_level_id' => 1, // Assuming the cdm_level_id you want to use
            'position_id' => 1, // Assuming the position_id you want to use
            'first_name' => 'Admin',
            'middle_name' => null,
            'last_name' => 'User',
            'name_suffix' => null,
            'alias' => null,
            'hiring_date' => now(),
            'last_day' => null,
            'employment_status_id' => $activeStatus->id, // Set the employment status ID
            'basic_pay' => 0,
            'rata' => 0,
            'comm_allowance' => 0,
            'transpo_allowance' => 0,
            'sss_number' => null,
            'pag_ibig_number' => null,
            'philhealth_number' => null,
            'tin' => null,
            'email' => 'admin@example.com',
            'phone_number' => null,
            'atm_account_number' => null,
            'bank' => null,
            'birthday' => null,
            'gender' => 'Male',
            'cdm_level' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => null, // Assuming you want to leave it null for now
        ]);
    }

    public function down()
    {
        DB::table('employees')->where('employee_number', '99999')->delete();
    }
}
