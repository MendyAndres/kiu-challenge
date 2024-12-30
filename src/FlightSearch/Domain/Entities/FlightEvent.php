<?php
declare(strict_types=1);

namespace Src\FlightSearch\Domain\Entities;

use Src\FlightSearch\Domain\ValueObjects\CityCode;
use Src\FlightSearch\Domain\ValueObjects\FlightNumber;
use Src\FlightSearch\Domain\ValueObjects\UTCDateTime;

class FlightEvent
{
    public function __construct(
        public FlightNumber $flightNumber,
        public UTCDateTime $departureTime,
        public UTCDateTime $arrivalTime,
        public CityCode $origin,
        public CityCode $destination,
    ){
        $this->ensureDatesAreValid($departureTime, $arrivalTime);
    }

    /**
     * Validates that the provided departure time is before the arrival time.
     *
     * @param UTCDateTime $departureTime The departure time to validate.
     * @param UTCDateTime $arrivalTime The arrival time to validate.
     * @return void
     * @throws \InvalidArgumentException If the departure time is after the arrival time.
     */
    private function ensureDatesAreValid(UTCDateTime $departureTime, UTCDateTime $arrivalTime): void
    {
        if($departureTime->isAfter($arrivalTime)) {
            throw new \InvalidArgumentException("Departure time must be before arrival time");
        }
    }

    /**
     * Calculates the duration between the departure time and arrival time in hours.
     *
     * @return int The duration in hours.
     */
    public function duration(): int
    {
        $interval = $this->departureTime->value()->diff($this->arrivalTime->value());
        return $interval->h + ($interval->days * 24);
    }

    public function origin(): CityCode
    {
        return $this->origin;
    }

    public function destination(): CityCode
    {
        return $this->destination;
    }

    public function flightNumber(): FlightNumber
    {
        return $this->flightNumber;
    }

    public function departureTime(): UTCDateTime
    {
        return $this->departureTime;
    }

    public function arrivalTime(): UTCDateTime
    {
        return $this->arrivalTime;
    }
}