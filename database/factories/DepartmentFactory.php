<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'code' => $this->faker->unique()->lexify('???'),
        ];
    }
}
