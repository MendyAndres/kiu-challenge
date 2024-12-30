<?php
declare(strict_types=1);

use Src\FlightSearch\Infrastructure\Http\Controllers\SearchJourneyController;

$router = new \AltoRouter();
$router->setBasePath('/api/v1');
$router->map('GET', '/journeys/search', ['controller' => SearchJourneyController::class, 'action' => 'searchJourneys'], 'search_journeys');

return $router;