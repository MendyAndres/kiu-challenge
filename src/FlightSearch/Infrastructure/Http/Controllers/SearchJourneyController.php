<?php

namespace Src\FlightSearch\Infrastructure\Http\Controllers;

use Src\FlightSearch\Application\UseCases\SearchJourneyUseCase;
use Src\FlightSearch\Infrastructure\Http\Helpers\RequestParser;

class SearchJourneyController
{
    public function __construct(private SearchJourneyUseCase $useCase){}

    /**
     * Searches for journeys based on the provided request parameters such as departure city, arrival city, and date.
     * Fetches the journeys and outputs the result in JSON format.
     *
     * @return void Outputs the search results as a JSON-encoded response.
     */
    public function searchJourneys(): void
    {
        $request = RequestParser::getParams();

        $departureCity = $request['departureCity'];
        $arrivalCity = $request['arrivalCity'];
        $date = $request['date'];

        $journeys = $this->useCase->execute($date, $departureCity, $arrivalCity);

        header('Content-Type: application/json', true, 200);
        echo json_encode($journeys);
    }
}