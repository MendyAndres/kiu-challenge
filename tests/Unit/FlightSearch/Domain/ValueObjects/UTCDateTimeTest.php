<?php

namespace Tests\Unit\FlightSearch\Domain\ValueObjects;

use Src\FlightSearch\Domain\ValueObjects\UTCDateTime;
use PHPUnit\Framework\TestCase;

class UTCDateTimeTest extends TestCase
{
    public function testValidUtcDateTime(): void
    {
        $datetime = new UTCDateTime('2024-12-24T00:00:00Z');
        $this->assertEquals('2024-12-24T00:00:00+00:00', $datetime->value()->format('c'));
    }

    public function testInvalidUtcDateTime(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new UTCDateTime('invalid-date');
    }


    public function testUtcDateTimeEquality(): void
    {
        $dateTime1 = new UTCDateTime('2024-09-12T12:00:00Z');
        $dateTime2 = new UTCDateTime('2024-09-12T12:00:00Z');
        $dateTime3 = new UTCDateTime('2024-09-12T13:00:00Z'); // Diferente hora

        $this->assertTrue($dateTime1->equals($dateTime2));
        $this->assertFalse($dateTime1->equals($dateTime3));
    }
}