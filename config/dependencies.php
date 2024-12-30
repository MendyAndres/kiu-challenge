<?php

use Src\FlightSearch\Domain\Contracts\FlightEventsProviderInterface;
use Src\FlightSearch\Infrastructure\API\ExternalFlightEventProvider;
use Src\FlightSearch\Infrastructure\DI\Container;

return static function (Container $container) {
    $container->bind(FlightEventsProviderInterface::class, ExternalFlightEventProvider::class);
};