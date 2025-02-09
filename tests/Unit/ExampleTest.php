<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ExampleTest extends TestCase
{
    #[Test]
    public function it_passes_as_a_placeholder(): void
    {
        $this->assertTrue(true);
    }
}
