<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Package;
use App\Models\Terminal;
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
                        ])
                    )
            );
    }
}
