<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Terminal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Inertia\Testing\AssertableInertia;

class TerminalControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_expected_structure(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Terminal::factory()->count(5)->create();

        $response = $this->get('/terminals');

        $response->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => 
                $page->component('Terminals/Index')
                    ->has('terminals', 5)
                    ->has('terminals.0', fn ($terminal) => 
                        $terminal->hasAll([
                            'id',
                            'name',
                            'city',
                            'latitude',
                            'longitude',
                        ])
                    )
            );
    }
}