<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Terminal;
use Database\Seeders\TerminalSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class TerminalSeederTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_seeds_terminals_correctly(): void
    {
        $this->seed(TerminalSeeder::class);

        $this->assertDatabaseCount('terminals', 10);

        $terminal = Terminal::first();

        $this->assertNotNull($terminal);
        $this->assertStringStartsWith('Terminal', $terminal->name);
    }
}
