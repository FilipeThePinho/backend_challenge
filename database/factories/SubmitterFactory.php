<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submitter>
 */
class SubmitterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email'        => fake()->unique()->safeEmail,
            'display_name' => fake()->userName,
            'real_name'    => fake()->name,
        ];
    }
}
