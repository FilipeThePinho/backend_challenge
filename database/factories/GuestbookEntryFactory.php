<?php

namespace Database\Factories;

use App\Models\Submitter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class GuestbookEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'   => fake()->words,
            'content' => fake()->sentence,
            'submitter_id' => Submitter::factory()->create()->id,
        ];
    }
}
