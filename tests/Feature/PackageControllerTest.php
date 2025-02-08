<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Package;
use App\Models\Terminal;
use App\Models\PackageScan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Inertia\Testing\AssertableInertia;

class PackageControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_expected_structure(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Terminal::factory()->count(5)->create();
        Package::factory()->count(10)->create();

        $response = $this->get('/packages');

        $response->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => 
                $page->component('Packages/Index')
                    ->has('packages.data', 10)
                    ->has('packages.data.0', fn ($package) => 
                        $package->hasAll([
                            'id',
                            'tracking_number',
                            'origin_terminal',
                            'destination_terminal',
                            'status',
                            'status_color',
                            'last_scanned_details',
                        ])
                    )
            );
    }

    #[Test]
    public function show_returns_expected_structure_with_scan_history(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    
        Terminal::factory()->count(5)->create();
    
        $package = Package::factory()->create();
    
        $response = $this->get("/packages/{$package->id}");
    
        $response->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => 
                $page->component('Packages/Show')
                    ->where('package.id', $package->id)
                    ->where('package.tracking_number', $package->tracking_number)
                    ->where('package.origin_terminal', $package->originTerminal->formatted_name)
                    ->where('package.destination_terminal', $package->destinationTerminal->formatted_name)
                    ->where('package.status', $package->status->value)
                    ->where('package.status_color', $package->status->color())
                    ->has('package.scan_history', $package->scans()->count())
            );
    }
}