<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Package;
use App\Models\PackageScan;
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

    /**
     * Configure the factory with post-creation logic.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Package $package) {
            if ($package->status === PackageStatus::PENDING) {
                return; // Pending packages should NOT have scans
            }

            if ($package->status === PackageStatus::DELIVERED) {
                // Delivered packages should have multiple scans between origin and destination
                $scansCount = rand(3, 6); // Random number of stops before delivery
                $previousScanTime = now()->subMinutes(rand(5000, 10000));

                // First scan at the origin
                PackageScan::create([
                    'package_id' => $package->id,
                    'terminal_id' => $package->origin_terminal_id,
                    'scanned_at' => $previousScanTime,
                ]);

                // Intermediate scans at random terminals
                for ($i = 0; $i < $scansCount; $i++) {
                    $previousScanTime = $previousScanTime->addMinutes(rand(60, 300));

                    $randomTerminal = Terminal::whereNotIn('id', [
                        $package->origin_terminal_id,
                        $package->destination_terminal_id,
                    ])->inRandomOrder()->first();

                    if ($randomTerminal) {
                        PackageScan::create([
                            'package_id' => $package->id,
                            'terminal_id' => $randomTerminal->id,
                            'scanned_at' => $previousScanTime,
                        ]);
                    }
                }

                // Last scan at the destination
                PackageScan::create([
                    'package_id' => $package->id,
                    'terminal_id' => $package->destination_terminal_id,
                    'scanned_at' => $previousScanTime->addMinutes(rand(60, 300)),
                ]);
            }

            if ($package->status === PackageStatus::IN_TRANSIT) {
                // In-Transit packages should have multiple scans but NOT at the destination
                $scansCount = rand(2, 5); // More stops in transit
                $previousScanTime = now()->subMinutes(rand(500, 5000));

                // First scan at the origin
                PackageScan::create([
                    'package_id' => $package->id,
                    'terminal_id' => $package->origin_terminal_id,
                    'scanned_at' => $previousScanTime,
                ]);

                // Intermediate stops
                for ($i = 0; $i < $scansCount; $i++) {
                    $previousScanTime = $previousScanTime->addMinutes(rand(60, 300));

                    $randomTerminal = Terminal::whereNotIn('id', [
                        $package->origin_terminal_id,
                        $package->destination_terminal_id,
                    ])->inRandomOrder()->first();

                    if ($randomTerminal) {
                        PackageScan::create([
                            'package_id' => $package->id,
                            'terminal_id' => $randomTerminal->id,
                            'scanned_at' => $previousScanTime,
                        ]);
                    }
                }
            }

            if ($package->status === PackageStatus::FAILED) {
                // Failed packages can have 0 or multiple scans but NOT at the destination
                if (rand(0, 1)) { // Randomly decide if it should have scans
                    $scanCount = rand(1, 4);
                    $previousScanTime = now()->subMinutes(rand(500, 5000));

                    for ($i = 0; $i < $scanCount; $i++) {
                        $previousScanTime = $previousScanTime->addMinutes(rand(60, 300));

                        $randomTerminal = Terminal::whereNotIn('id', [$package->destination_terminal_id])
                            ->inRandomOrder()
                            ->first();

                        if ($randomTerminal) {
                            PackageScan::create([
                                'package_id' => $package->id,
                                'terminal_id' => $randomTerminal->id,
                                'scanned_at' => $previousScanTime,
                            ]);
                        }
                    }
                }
            }
        });
    }
}