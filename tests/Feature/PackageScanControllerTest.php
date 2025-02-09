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
use Illuminate\Support\Facades\Route;

class PackageScanControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_routes_are_loaded(): void
    {
        $routes = Route::getRoutes();
        $this->assertTrue($routes->hasNamedRoute('api.packages.scans.store'));
    }

    #[Test]
    public function store_adds_a_scan_and_redirects_with_banner(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $terminals = Terminal::factory()->count(5)->create();
        $package = Package::factory()->create();

        $scanData = [
            'terminal_id' => $terminals->first()->id,
            'scanned_at' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->post("/packages/{$package->id}/scans", $scanData);

        $response->assertRedirect();
        $this->assertDatabaseHas('package_scans', array_merge($scanData, ['package_id' => $package->id]));
    }

    #[Test]
    public function store_api_adds_a_scan_and_returns_json_response(): void
    {
        $terminals = Terminal::factory()->count(5)->create();
        $package = Package::factory()->create();

        $scanData = [
            'terminal_id' => $terminals->first()->id,
            'scanned_at' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson("/packages/{$package->id}/update-scans", $scanData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Scan added successfully',
                'scan' => [
                    'terminal_id' => $scanData['terminal_id'],
                    'scanned_at' => $scanData['scanned_at'],
                    'package_id' => $package->id,
                ]
            ]);

        $this->assertDatabaseHas('package_scans', array_merge($scanData, ['package_id' => $package->id]));
    }
}
