<?php

namespace Tests\Unit\FlightSearch\Domain\ValueObjects;

use Src\FlightSearch\Domain\ValueObjects\FlightNumber;
use PHPUnit\Framework\TestCase;

class FlightNumberTest extends TestCase
{
    public function testValidFlightNumber(): void
    {
        $flightNumber = new FlightNumber('XX1234');
        $this->assertEquals('XX1234', $flightNumber->value());
    }

    public function testInvalidFlightNumber(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new FlightNumber('XX12345');
    }

    public function testFlightNumberEquality(): void
    {
        $flightNumber1 = new FlightNumber('XX1234');
        $flightNumber2 = new FlightNumber('XX1234');
        $flightNumber3 = new FlightNumber('XX5678');
        $this->assertTrue($flightNumber1->equals($flightNumber2));
        $this->assertFalse($flightNumber1->equals($flightNumber3));
    }
}