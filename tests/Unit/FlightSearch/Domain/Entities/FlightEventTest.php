<?php

namespace Tests\Unit\FlightSearch\Domain\Entities;

use Src\FlightSearch\Domain\Entities\FlightEvent;
use Src\FlightSearch\Domain\ValueObjects\CityCode;
use Src\FlightSearch\Domain\ValueObjects\FlightNumber;
use Src\FlightSearch\Domain\ValueObjects\UTCDateTime;
use PHPUnit\Framework\TestCase;

class FlightEventTest extends TestCase
{
    /**
     * Tests the creation of a valid FlightEvent instance and verifies
     * that all properties (flight number, origin, and destination)
     * are correctly set and retrievable.
     *
     * @return void
     */
    public function testValidFlightEvent(): void
    {
        $flightEvent = new FlightEvent(
            new FlightNumber('XX1234'),
            new UTCDateTime('2024-12-24T12:00:00Z'),
            new UTCDateTime('2024-12-24T23:00:00Z'),
            new CityCode('BUE'),
            new CityCode('MAD'),
        );

        $this->assertEquals('XX1234', $flightEvent->flightNumber()->value());
        $this->assertEquals('BUE', $flightEvent->origin()->value());
        $this->assertEquals('MAD', $flightEvent->destination()->value());
    }
}