<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CDMLevelFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => 'Level ' . $this->faker->numberBetween(1, 10),
            'code' => 'L' . $this->faker->unique()->numberBetween(1, 10),
            'description' => $this->faker->sentence,
        ];
    }
}
