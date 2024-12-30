<?php

namespace Src\FlightSearch\Infrastructure\API;

use Src\FlightSearch\Domain\Contracts\FlightEventsProviderInterface;
use Src\FlightSearch\Domain\Entities\FlightEvent;
use Src\FlightSearch\Domain\ValueObjects\CityCode;
use Src\FlightSearch\Domain\ValueObjects\FlightNumber;
use Src\FlightSearch\Domain\ValueObjects\UTCDateTime;

class ExternalFlightEventProvider implements FlightEventsProviderInterface
{
    /**
     * Retrieves a list of flight events based on the specified date, departure city, and arrival city.
     *
     * @param string $date The date for which the flight events are to be retrieved, in YYYY-MM-DD format.
     * @param string $departureCity The code of the city where the flight departs from.
     * @param string $arrivalCity The code of the city where the flight arrives.
     *
     * @return array An array of FlightEvent objects representing the flights matching the specified criteria.
     */
    public function getFlightEvents(string $date, string $departureCity, string $arrivalCity): array
    {
        $flightEvents = $this->fetchFromAPI($date, $departureCity, $arrivalCity);

        return array_map(function ($eventData) {

            return new FlightEvent(
                new FlightNumber($eventData['flight_number']),
                new UTCDateTime($eventData['departure_datetime']),
                new UTCDateTime($eventData['arrival_datetime']),
                new CityCode($eventData['departure_city']),
                new CityCode($eventData['arrival_city'])
            );
        }, $flightEvents);
    }

    protected function fetchFromAPI(string $date, string $departureCity, string $arrivalCity): array
    {
        //TODO: implement api
        return [
            [
                "flight_number" => "IB1234",
                "departure_city" => "MAD",
                "arrival_city" => "BUE",
                "departure_datetime" => "2023-12-31T23:59:59.000Z",
                "arrival_datetime" => "2024-01-01T00:00:00.000Z",
            ],
            [
                "flight_number" => "IB1234",
                "departure_city" => "BUE",
                "arrival_city" => "MDZ",
                "departure_datetime" => "2024-01-01T01:00:00.000Z",
                "arrival_datetime" => "2024-01-01T02:30:00.000Z",
            ],
            [
                "flight_number" => "IB1234",
                "departure_city" => "MAD",
                "arrival_city" => "MDZ",
                "departure_datetime" => "2023-12-31T23:59:59.000Z",
                "arrival_datetime" => "2024-01-01T06:00:00.000Z",
            ],
        ];
    }
}