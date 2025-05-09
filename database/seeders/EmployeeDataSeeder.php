<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;
use App\Models\EmploymentType;
use App\Models\EmploymentStatus;

class EmployeeDataSeeder extends Seeder
{
    public function run()
    {
        // Seed genders
        $genders = ['Male', 'Female', 'Non-binary', 'Genderqueer', 'Genderfluid', 'Agender', 'Bigender', 'Two-Spirit', 'Other'];
        foreach ($genders as $gender) {
            Gender::create(['name' => $gender]);
        }

        // Seed employment types
        $employmentTypes = ['Regular', 'Contractual', 'Probationary', 'Part-time', 'Project-based'];
        foreach ($employmentTypes as $type) {
            EmploymentType::firstOrCreate(['name' => $type]);
        }

        // Seed employment statuses
        $employmentStatuses = ['Active', 'Inactive', 'On Leave', 'Suspended', 'Terminated'];
        foreach ($employmentStatuses as $status) {
            EmploymentStatus::firstOrCreate(['name' => $status]);
        }

        // You can add more seed data for other tables as needed
    }
}
