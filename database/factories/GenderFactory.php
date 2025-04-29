<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GenderFactory extends Factory
{
    public function definition()
    {
        $genders = ['Male', 'Female', 'Non-binary', 'Genderqueer', 'Genderfluid', 'Agender', 'Bigender', 'Two-Spirit', 'Other'];

        return [
            'name' => $this->faker->randomElement($genders),
        ];
    }
}
