<?php
declare(strict_types=1);

namespace Src\FlightSearch\Application\DTOs;

use Src\FlightSearch\Domain\Entities\FlightEvent;

class FlightEventDTO
{
    public function __construct(
        public string $flightNumber,
        public \DateTimeImmutable $departureTime,
        public \DateTimeImmutable $arrivalTime,
        public string $origin,
        public string $destination
    ) {

    }

    /**
     * Creates an instance of the class from a FlightEvent object.
     *
     * @param FlightEvent $flightEvent The flight event containing flight details.
     * @return self Returns a new instance of the class populated with values from the FlightEvent.
     */
    public static function fromFlightEvent(FlightEvent $flightEvent): self
    {
        return new self(
            $flightEvent->flightNumber()->value(),
            $flightEvent->departureTime()->value(),
            $flightEvent->arrivalTime()->value(),
            $flightEvent->origin()->value(),
            $flightEvent->destination()->value()
        );
    }


}