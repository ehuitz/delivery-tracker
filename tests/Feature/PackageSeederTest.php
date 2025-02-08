<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Package;
use App\Models\PackageScan;
use App\Models\Terminal;
use App\Enums\PackageStatus;
use Database\Seeders\PackageSeeder;
use Database\Seeders\TerminalSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PackageSeederTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_seeds_packages_correctly(): void
    {
        $this->seed(TerminalSeeder::class);
        $this->seed(PackageSeeder::class);

        $this->assertDatabaseCount('packages', 20);

        $package = Package::first();

        $this->assertNotNull($package);
        $this->assertNotNull($package->origin_terminal_id);
        $this->assertNotNull($package->destination_terminal_id);
        $this->assertNotEquals($package->origin_terminal_id, $package->destination_terminal_id);
    }

    #[Test]
    public function pending_package_should_have_no_scans(): void
    {
        $this->seed(TerminalSeeder::class);
        $this->seed(PackageSeeder::class);

        $pendingPackages = Package::where('status', PackageStatus::PENDING)->get();

        foreach ($pendingPackages as $package) {
            $this->assertDatabaseMissing('package_scans', [
                'package_id' => $package->id,
            ]);
        }
    }

    #[Test]
    public function delivered_package_should_have_origin_and_destination_scans(): void
    {
        $this->seed(TerminalSeeder::class);
        $this->seed(PackageSeeder::class);

        $deliveredPackages = Package::where('status', PackageStatus::DELIVERED)->get();

        foreach ($deliveredPackages as $package) {
            $this->assertDatabaseHas('package_scans', [
                'package_id' => $package->id,
                'terminal_id' => $package->origin_terminal_id,
            ]);

            $this->assertDatabaseHas('package_scans', [
                'package_id' => $package->id,
                'terminal_id' => $package->destination_terminal_id,
            ]);
        }
    }

    #[Test]
    public function in_transit_package_should_have_at_least_one_scan_but_not_at_destination(): void
    {
        $this->seed(TerminalSeeder::class);
        $this->seed(PackageSeeder::class);

        $inTransitPackages = Package::where('status', PackageStatus::IN_TRANSIT)->get();

        foreach ($inTransitPackages as $package) {
            $this->assertGreaterThan(0, PackageScan::where('package_id', $package->id)->count());

            $this->assertDatabaseMissing('package_scans', [
                'package_id' => $package->id,
                'terminal_id' => $package->destination_terminal_id,
            ]);
        }
    }

    #[Test]
    public function failed_package_can_have_zero_or_more_scans_but_not_at_destination(): void
    {
        $this->seed(TerminalSeeder::class);
        $this->seed(PackageSeeder::class);

        $failedPackages = Package::where('status', PackageStatus::FAILED)->get();

        foreach ($failedPackages as $package) {
            // It can have 0 or more scans
            $this->assertGreaterThanOrEqual(0, PackageScan::where('package_id', $package->id)->count());

            // Ensure no scan exists at the destination terminal
            $this->assertDatabaseMissing('package_scans', [
                'package_id' => $package->id,
                'terminal_id' => $package->destination_terminal_id,
            ]);
        }
    }
}
