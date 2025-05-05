<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\EmploymentStatus;
use App\Models\Position;

class InsertAdminUserIntoEmployeesTable extends Migration
{
    public function up()
    {
        // Ensure all required reference data exists
        $activeStatus = EmploymentStatus::firstOrCreate(
            ['name' => 'Active'],
            ['name' => 'Active']
        );

        // Create default admin position if it doesn't exist
        $adminPosition = Position::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'System Administrator',
                'code' => 'SYSADMIN',
                'description' => 'Top-level system administrator',
                'status' => true
            ]
        );

        // Insert admin user only if it doesn't exist
        if (!DB::table('employees')->where('employee_number', '00000')->exists()) {
            DB::table('employees')->insert([
                'employee_number' => '00000',
                'agency_id' => 1,
                'department_id' => 1,
                'cdm_level_id' => 1,
                'position_id' => $adminPosition->id,
                'first_name' => 'Admin',
                'middle_name' => null,
                'last_name' => 'User',
                'name_suffix' => null,
                'alias' => null,
                'hiring_date' => now(),
                'last_day' => null,
                'employment_status_id' => $activeStatus->id,
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
                'created_at' => now(),
                'updated_at' => now(),
                'user_id' => null,
            ]);
        }
    }

    public function down()
    {
        DB::table('employees')->where('employee_number', '00000')->delete();
    }
}