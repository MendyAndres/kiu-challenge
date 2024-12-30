<?php
declare(strict_types=1);

namespace Src\FlightSearch\Application\UseCases;

use Src\FlightSearch\Application\DTOs\FlightEventDTO;
use Src\FlightSearch\Application\DTOs\JourneyDTO;
use Src\FlightSearch\Domain\Contracts\FlightEventsProviderInterface;
use Src\FlightSearch\Domain\Entities\FlightEvent;
use Src\FlightSearch\Domain\Entities\Journey;
use Src\FlightSearch\Domain\ValueObjects\CityCode;
use Src\FlightSearch\Domain\ValueObjects\JourneyConstraints;

class SearchJourneyUseCase
{
    public function __construct(private readonly FlightEventsProviderInterface $flightEventsProvider){}

    /**
     * Executes the journey generation process based on the provided date, origin, and destination.
     *
     * @param string $date The date for which the journey is to be planned.
     * @param string $from The origin location of the journey.
     * @param string $to The destination location of the journey.
     *
     * @return array An array containing the generated journeys based on the specified parameters.
     */
    public function execute(string $date, string $from, string $to): array
    {
        $constraints = new JourneyConstraints(24, 2, 4);
        $flightEvents = $this->flightEventsProvider->getFlightEvents($date, $from, $to);
        return $this->generateJourneys($flightEvents, $constraints, $from, $to);
    }

    /**
     * Generates a list of possible journeys based on given flight events, constraints, and route information.
     *
     * @param array $flightEvents An array of flight event objects representing available flight data.
     * @param JourneyConstraints $constraints Constraints used to validate and filter potential journeys.
     * @param string $from The origin location for the journey.
     * @param string $to The destination location for the journey.
     * @return array An array of JourneyDTOs, valid journeys that satisfy the constraints and route requirements.
     */
    private function generateJourneys(
        array $flightEvents,
        JourneyConstraints $constraints,
        string $from,
        string $to
    ): array
    {
        $journeys = [];
        //first event
        foreach ($flightEvents as $firstFlightEvent) {

            // Direct Flights Event
            if ($this->isDirectFlight($firstFlightEvent, $from, $to)){
                $this->addJourneyIfValid($journeys, [$firstFlightEvent], $constraints);
            }

            // Valids connection
            foreach ($flightEvents as $secondFlightEvent) {
                if ($firstFlightEvent->destination->equals($secondFlightEvent->origin)) {
                    $this->addJourneyIfValid($journeys, [$firstFlightEvent, $secondFlightEvent], $constraints);
                }
            }
        }
        return $journeys;
    }

    /**
     * Determines if a given flight event is a direct flight between the specified origin and destination.
     *
     * @param FlightEvent $flightEvent The flight event to evaluate.
     * @param string $from The origin location code for the flight.
     * @param string $to The destination location code for the flight.
     * @return bool True if the flight event represents a direct flight from the specified origin to destination, false otherwise.
     */
    private function isDirectFlight(FlightEvent $flightEvent, string $from, string $to): bool
    {
        $fromCode = new CityCode($from);
        $toCode = new CityCode($to);

        return $flightEvent->origin->equals($fromCode) && $flightEvent->destination->equals($toCode);
    }

    /**
     * Adds a valid journey to the list of journeys if it satisfies given constraints.
     *
     * @param array $journeys A reference to the array where valid journeys will be added.
     * @param array $flightEvents An array of flight event objects representing a sequence of flights for the journey.
     * @param JourneyConstraints $constraints Constraints used to validate the journey's eligibility.
     * @return void This method does not return a value, as it modifies the $journeys array by reference.
     */
    private function addJourneyIfValid(array &$journeys, array $flightEvents, JourneyConstraints $constraints): void
    {
        try {
            $journey = new Journey($flightEvents, $constraints);

            if ($journey->isValid()) {
               $eventDTOs =  array_map(function ($flightEvent) {
                    return FlightEventDTO::fromFlightEvent($flightEvent);
                }, $flightEvents);
                $journeys[] = JourneyDTO::toDto($eventDTOs);
            }
        } catch (\InvalidArgumentException $e) {
            // Ignorar combinaciones no válidas y continuar
        }
    }
}

