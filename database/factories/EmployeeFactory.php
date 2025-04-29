<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Agency;
use App\Models\Department;
use App\Models\CDMLevel;
use App\Models\Gender;
use App\Models\EmploymentType;
use App\Models\Position;
use App\Models\EmploymentStatus;

class EmployeeFactory extends Factory
{
    public function definition()
    {
        return [
            'employee_number' => $this->faker->unique()->numerify('EMP#####'),
            'agency_id' => Agency::factory(),
            'department_id' => Department::factory(),
            'cdm_level_id' => CDMLevel::factory(),
            'gender_id' => Gender::factory(),
            'employment_type_id' => EmploymentType::factory(),
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->optional()->lastName,
            'name_suffix' => $this->faker->optional()->randomElement(['Jr.', 'Sr.', 'II', 'III', 'IV']),
            'alias' => $this->faker->optional()->userName,
            'position_id' => Position::factory(),
            'hiring_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'last_day' => $this->faker->optional()->dateTimeBetween('-1 year', '+1 year'),
            'employment_status_id' => EmploymentStatus::factory(),
            'tenure' => $this->faker->randomElement(['0-1 year', '1-3 years', '3-5 years', '5+ years']),
            'basic_pay' => $this->faker->randomFloat(2, 10000, 50000),
            'rata' => $this->faker->randomFloat(2, 0, 5000),
            'comm_allowance' => $this->faker->randomFloat(2, 0, 3000),
            'transpo_allowance' => $this->faker->randomFloat(2, 0, 2000),
            'parking_toll_allowance' => $this->faker->randomFloat(2, 0, 1000),
            'clothing_allowance' => $this->faker->randomFloat(2, 0, 1500),
            'total_package' => $this->faker->randomFloat(2, 15000, 60000),
            'atm_account_number' => $this->faker->optional()->creditCardNumber,
            'birthday' => $this->faker->dateTimeBetween('-60 years', '-20 years'),
            'sss_number' => $this->faker->optional()->numerify('##-#######-#'),
            'philhealth_number' => $this->faker->optional()->numerify('##-#########-#'),
            'pag_ibig_number' => $this->faker->optional()->numerify('####-####-####'),
            'tin' => $this->faker->optional()->numerify('###-###-###-###'),
        ];
    }
}
