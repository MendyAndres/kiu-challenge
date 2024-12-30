<?php
declare(strict_types=1);

namespace Src\FlightSearch\Domain\Contracts;

interface FlightEventsProviderInterface
{
    public function getFlightEvents(string $date, string $departureCity, string $arrivalCity): array;
}