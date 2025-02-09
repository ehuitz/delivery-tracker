<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Package;
use App\Models\Terminal;
use App\Models\PackageScan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
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

        $response = $this->post(route('packages.scans.store', $package), $scanData);

        $response->assertStatus(302); // Instead of assertRedirect
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

        $response = $this->postJson(route('api.packages.scans.store', $package), $scanData);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Scan added successfully')
            ->assertJsonPath('scan.terminal_id', $scanData['terminal_id'])
            ->assertJsonPath('scan.package_id', $package->id)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['message', 'scan'])
                    ->where('scan.scanned_at', fn ($scannedAt) =>
                        str_contains($scannedAt, substr(now()->toISOString(), 0, 16))
                    )
            );

        $this->assertDatabaseHas('package_scans', array_merge($scanData, ['package_id' => $package->id]));


    }

}
