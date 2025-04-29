<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CDMLevel;

class PositionFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle,
            'cdm_level_id' => CDMLevel::factory(),
        ];
    }
}
