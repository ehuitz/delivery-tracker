<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Package;
use App\Models\Terminal;
use App\Enums\PackageStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{

    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $origin = Terminal::inRandomOrder()->first();
        $destination = Terminal::where('id', '!=', $origin->id)->inRandomOrder()->first();

        return [
            'tracking_number' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'origin_terminal_id' => $origin->id,
            'destination_terminal_id' => $destination->id,
            'status' => $this->faker->randomElement(PackageStatus::toArray()),
        ];
    }
}
