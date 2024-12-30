<?php

namespace Tests\Unit\FlightSearch\Domain\Entities;

use Src\FlightSearch\Domain\Entities\FlightEvent;
use Src\FlightSearch\Domain\Entities\Journey;
use Src\FlightSearch\Domain\ValueObjects\CityCode;
use Src\FlightSearch\Domain\ValueObjects\FlightNumber;
use Src\FlightSearch\Domain\ValueObjects\JourneyConstraints;
use Src\FlightSearch\Domain\ValueObjects\UTCDateTime;
use PHPUnit\Framework\TestCase;

class JourneyTest extends TestCase
{
    /**
     * Tests the validity of a journey based on its flights and given constraints.
     *
     * @return void
     */
    public function testValidJourney(): void
    {
        $flight1 = new FlightEvent(
            new FlightNumber('XX1234'),
            new UTCDateTime('2024-09-12T12:00:00Z'),
            new UTCDateTime('2024-09-12T23:00:00Z'),
            new CityCode('BUE'),
            new CityCode('MAD')
        );

        $flight2 = new FlightEvent(
            new FlightNumber('XX5678'),
            new UTCDateTime('2024-09-13T01:00:00Z'),
            new UTCDateTime('2024-09-13T03:00:00Z'),
            new CityCode('MAD'),
            new CityCode('BCN')
        );

        $constraints = new JourneyConstraints(24, 2, 4);

        $journey = new Journey([$flight1, $flight2], $constraints);

        $this->assertTrue($journey->isValid());
    }

    /**
     * Tests that a journey is invalid when the connection time between flights exceeds the allowed limit.
     *
     * @return void
     */
    public function testInvalidJourneyDueToConnectionTime(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $flight1 = new FlightEvent(
            new FlightNumber('XX1234'),
            new UTCDateTime('2024-09-12T12:00:00Z'),
            new UTCDateTime('2024-09-12T18:00:00Z'),
            new CityCode('BUE'),
            new CityCode('MAD')
        );

        $flight2 = new FlightEvent(
            new FlightNumber('XX5678'),
            new UTCDateTime('2024-09-12T23:00:00Z'), // Conexión > 4 horas
            new UTCDateTime('2024-09-13T03:00:00Z'),
            new CityCode('MAD'),
            new CityCode('BCN')
        );

        $constraints = new JourneyConstraints(24, 2, 4);
        new Journey([$flight1, $flight2], $constraints);
    }
}