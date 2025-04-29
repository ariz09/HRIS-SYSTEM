<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AgencyFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'code' => $this->faker->unique()->lexify('???'),
        ];
    }
}
