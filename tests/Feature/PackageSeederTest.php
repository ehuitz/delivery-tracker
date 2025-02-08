<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Package;
use App\Models\Terminal;
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

        $this->assertDatabaseCount('packages', 50);

        $package = Package::first();

        $this->assertNotNull($package);
        $this->assertNotNull($package->origin_terminal_id);
        $this->assertNotNull($package->destination_terminal_id);
        $this->assertNotEquals($package->origin_terminal_id, $package->destination_terminal_id);
    }
}