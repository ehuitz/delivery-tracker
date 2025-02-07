<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Terminal>
 */
class TerminalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Terminal ' . $this->faker->unique()->numberBetween(1, 1000),
            'city' => $this->faker->city(),
            'latitude' => $this->faker->latitude(58, 62),
            'longitude' => $this->faker->longitude(12, 26),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
