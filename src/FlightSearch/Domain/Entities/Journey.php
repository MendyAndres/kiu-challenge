<?php
declare(strict_types=1);

namespace Src\FlightSearch\Domain\Entities;

use Src\FlightSearch\Domain\ValueObjects\JourneyConstraints;

class Journey
{
    public function __construct(private array $flightEvents, private readonly JourneyConstraints $constraints)
    {
        $this->ensureValidFlightEventSequence($this->flightEvents);
    }

    /**
     * Validates a sequence of flight events to ensure they adhere to specific rules,
     * such as chronological order and maximum connection time.
     *
     * @param array $flightEvents An array of FlightEvent objects, each of which
     * @return void
     * @throws \InvalidArgumentException If the journey contains more than two flight events,
     *         if the flight events are not in chronological order, or if the connection time
     *         between consecutive flight events exceeds the maximum allowed duration.
     */
    private function ensureValidFlightEventSequence(array $flightEvents): void
    {
        if(count($flightEvents) > 2) {
            throw new \InvalidArgumentException("Journey cannot have more than 2 flight events");
        }

        for ($i = 0; $i < count($flightEvents) - 1; $i++) {
            $currentArrival = $flightEvents[$i]->arrivalTime();
            $nextDeparture = $flightEvents[$i + 1]->departureTime();

            if ($currentArrival->isAfter($nextDeparture)) {
                throw new \InvalidArgumentException("Flight events must be in chronological order.");
            }

            $connectionTime = $currentArrival->value()->diff($nextDeparture->value())->h;
            if ($connectionTime > $this->constraints->maxConnectionTimeInHours()) {
                throw new \InvalidArgumentException("Connection time exceeds maximum allowed.");
            }
        }
    }

    /**
     * Calculates the total duration of the journey in hours, based on the
     * departure time of the first flight event and the arrival time of the last flight event.
     *
     * @return int The total duration of the journey in hours.
     */
    public function duration(): int
    {
        $firstEvent = $this->flightEvents[0];
        $lastEvent = end($this->flightEvents);

        $interval = $firstEvent->departureTime()->value()->diff($lastEvent->arrivalTime()->value());
        return $interval->h + ($interval->days * 24);
    }

    /**
     * Determines if the current duration of the instance complies with the allowed maximum duration constraint.
     *
     * @return bool True if the duration is less than or equal to the maximum duration allowed, false otherwise.
     */
    public function isValid(): bool
    {
        return $this->duration() <= $this->constraints->maxDurationInHours();
    }

    /**
     * Retrieves the list of flight events.
     *
     * @return array The array of FlightEvent Objects.
     */
    public function flightEvents(): array
    {
        return $this->flightEvents;
    }
}