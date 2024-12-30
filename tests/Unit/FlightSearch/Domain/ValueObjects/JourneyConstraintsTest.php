<?php

namespace Tests\Unit\FlightSearch\Domain\ValueObjects;

use Src\FlightSearch\Domain\ValueObjects\JourneyConstraints;
use PHPUnit\Framework\TestCase;

class JourneyConstraintsTest extends TestCase
{
    public function testValidJourneyConstraints(): void
    {
        $constraints = new JourneyConstraints(24, 2, 4);

        $this->assertEquals(24, $constraints->maxDurationInHours());
        $this->assertEquals(2, $constraints->maxConnections());
        $this->assertEquals(4, $constraints->maxConnectionTimeInHours());
    }

    public function testInvalidContraints(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new JourneyConstraints(0, 2, 4);
    }
}