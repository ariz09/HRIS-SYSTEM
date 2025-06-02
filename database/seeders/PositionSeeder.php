<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $positions = [
            ['name' => 'Cheif Executive Officer', 'cdm_level_id' => 1],
            ['name' => 'Cheif Fiannce Officer', 'cdm_level_id' => 1],
            ['name' => 'Cheif Creative Officer', 'cdm_level_id' => 1],
            ['name' => 'Managing Partner', 'cdm_level_id' => 1],
            ['name' => 'Managing Director', 'cdm_level_id' => 1],

            ['name' => 'Internal Auditor', 'cdm_level_id' => 2],

            ['name' => 'HRAD Manager', 'cdm_level_id' => 3],
            ['name' => 'Creative Director', 'cdm_level_id' => 3],
            ['name' => 'Head of Account', 'cdm_level_id' => 3],
            ['name' => 'Head of Digital Production', 'cdm_level_id' => 3],
            ['name' => 'Head of Product Development', 'cdm_level_id' => 3],

            ['name' => 'Disbursement Supervisor', 'cdm_level_id' => 4],
            ['name' => 'Billing & Collection Officer', 'cdm_level_id' => 4],
            ['name' => 'Project Auditor', 'cdm_level_id' => 4],
            ['name' => 'HRAD Supervisor', 'cdm_level_id' => 4],
            ['name' => 'Warehouse Supervisor', 'cdm_level_id' => 4],
            ['name' => 'Digital Production Supervisor', 'cdm_level_id' => 4],
            ['name' => 'Senior Developer', 'cdm_level_id' => 4],
            ['name' => 'Lead Developer', 'cdm_level_id' => 4],
            ['name' => 'Business Unit Supervisor', 'cdm_level_id' => 4],
            ['name' => 'Group Account Director', 'cdm_level_id' => 4],
            ['name' => 'Senior Account Executive', 'cdm_level_id' => 4],
            ['name' => 'Account Director', 'cdm_level_id' => 4],
            ['name' => 'Operations Manager', 'cdm_level_id' => 4],
            ['name' => 'Compliance Officer', 'cdm_level_id' => 4],

            ['name' => 'Associate CD - Art', 'cdm_level_id' => 5],
            ['name' => 'Senior Account Manager', 'cdm_level_id' => 5],
            ['name' => 'Senior Account Manager II - Acquisition', 'cdm_level_id' => 5],
            ['name' => 'Senior Account Manager II - Client Servicing', 'cdm_level_id' => 5],
            ['name' => 'Senior Project Manager', 'cdm_level_id' => 5],
            ['name' => 'Senior Digital Producer', 'cdm_level_id' => 5],
            ['name' => 'Admin Officer', 'cdm_level_id' => 5],
            ['name' => 'Administrative & Executive Officer', 'cdm_level_id' => 5],
            ['name' => 'Project Supervisor', 'cdm_level_id' => 5],
            ['name' => 'Recruitment Officer', 'cdm_level_id' => 5],
            ['name' => 'Logistics Officer', 'cdm_level_id' => 5],
            ['name' => 'Procurement Officer', 'cdm_level_id' => 5],
            ['name' => 'Booking Officer', 'cdm_level_id' => 5],
            ['name' => 'Senior Copywriter', 'cdm_level_id' => 5],
            ['name' => 'Senior Creative Writer', 'cdm_level_id' => 5],
            ['name' => 'Mid Developer', 'cdm_level_id' => 5],
            ['name' => 'System Analyst', 'cdm_level_id' => 5],
            ['name' => 'Executive Assistant', 'cdm_level_id' => 5],
            ['name' => 'HR Talent Acquisition - Recruitment Officer', 'cdm_level_id' => 5],
            ['name' => 'Compensation & Payroll Officer', 'cdm_level_id' => 5],
            ['name' => 'Warehouse Admin Officer', 'cdm_level_id' => 5],
            ['name' => 'Project Foreman', 'cdm_level_id' => 5],

            ['name' => 'Audit Staff', 'cdm_level_id' => 6],
            ['name' => 'Accounting Assistant', 'cdm_level_id' => 6],
            ['name' => 'Finance Assistant', 'cdm_level_id' => 6],
            ['name' => 'Bookkeeper', 'cdm_level_id' => 6],
            ['name' => 'Audit Associate', 'cdm_level_id' => 6],
            ['name' => 'Admin Associate', 'cdm_level_id' => 6],
            ['name' => 'HR Generalist / OD Associate', 'cdm_level_id' => 6],
            ['name' => 'IT Admin', 'cdm_level_id' => 6],
            ['name' => 'Utility / Messenger', 'cdm_level_id' => 6],
            ['name' => 'Warehouseman', 'cdm_level_id' => 6],
            ['name' => 'Digital Producer', 'cdm_level_id' => 6],
            ['name' => 'Copywriter', 'cdm_level_id' => 6],
            ['name' => 'Creaive Writer', 'cdm_level_id' => 6],
            ['name' => 'Art Director', 'cdm_level_id' => 6],
            ['name' => 'Graphic and Motion Designer', 'cdm_level_id' => 6],
            ['name' => 'Junior Developer', 'cdm_level_id' => 6],
            ['name' => 'Account Manager', 'cdm_level_id' => 6],
            ['name' => 'Project Manager', 'cdm_level_id' => 6],
            ['name' => 'Admin Assistant', 'cdm_level_id' => 6],
            ['name' => 'Social Media Manager', 'cdm_level_id' => 6],
            ['name' => 'Printing Officer', 'cdm_level_id' => 6],
            ['name' => 'Fabrication Associate', 'cdm_level_id' => 6],
            ['name' => 'Junior Marketing Associate', 'cdm_level_id' => 6],
        ];

        $positions = array_map(function ($position) use ($now) {
            return array_merge($position, [
                'status' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $positions);

        DB::table('positions')->insert($positions);
    }
}
