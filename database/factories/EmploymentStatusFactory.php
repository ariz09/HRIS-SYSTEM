<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmploymentStatusFactory extends Factory
{
    public function definition()
    {
        $statuses = ['Active', 'Inactive', 'On Leave', 'Suspended', 'Terminated'];

        return [
            'name' => $this->faker->randomElement($statuses),
        ];
    }
}
