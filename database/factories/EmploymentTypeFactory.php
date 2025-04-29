<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmploymentTypeFactory extends Factory
{
    public function definition()
    {
        $types = ['Regular', 'Contractual', 'Probationary', 'Part-time', 'Project-based'];

        return [
            'name' => $this->faker->randomElement($types),
        ];
    }
}
