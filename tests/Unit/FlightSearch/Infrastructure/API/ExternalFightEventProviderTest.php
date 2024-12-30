<?php
namespace Tests\Unit\FlightSearch\Infrastructure\API;

use PHPUnit\Framework\TestCase;
use Src\FlightSearch\Domain\Entities\FlightEvent;
use Src\FlightSearch\Domain\ValueObjects\CityCode;
use Src\FlightSearch\Infrastructure\API\ExternalFlightEventProvider;

class ExternalFightEventProviderTest extends TestCase
{
    /**
     * Tests that the getFlightEvents method returns valid flight event data
     * based on the provided mock API response.
     *
     * @return void
     */
    public function testGetFlightEventsReturnsValidData()
    {
        $apiResponse = [
            [
                'flight_number' => 'XX1234',
                'departure_datetime' => '2024-09-12T12:00:00Z',
                'arrival_datetime' => '2024-09-12T18:00:00Z',
                'departure_city' => 'BUE',
                'arrival_city' => 'MAD'
            ],
            [
                'flight_number' => 'XX5678',
                'departure_datetime' => '2024-09-12T20:00:00Z',
                'arrival_datetime' => '2024-09-12T23:00:00Z',
                'departure_city' => 'MAD',
                'arrival_city' => 'BCN'
            ]
        ];

        $provider = $this->getMockBuilder(ExternalFlightEventProvider::class)
            ->onlyMethods(['fetchFromAPI'])
            ->getMock();

        $provider->method('fetchFromAPI')->willReturn($apiResponse);

        $flightEvents = $provider->getFlightEvents('2024-09-12', 'BUE', 'BCN');

        $this->assertCount(2, $flightEvents);
        $this->assertInstanceOf(FlightEvent::class, $flightEvents[0]);
        $this->assertEquals(new CityCode('BUE'), $flightEvents[0]->origin);
        $this->assertEquals(new CityCode('MAD'), $flightEvents[0]->destination);
    }

    /**
     * Tests that the getFlightEvents method returns an empty result
     * when the mock API response contains no data.
     *
     * @return void
     */
    public function testGetFlightEventsReturnsEmptyWhenAPIResponseIsEmpty(): void
    {
        // Simular respuesta vacía de la API
        $apiResponse = [];

        $provider = $this->getMockBuilder(ExternalFlightEventProvider::class)
            ->onlyMethods(['fetchFromAPI'])
            ->getMock();

        $provider->method('fetchFromAPI')
            ->willReturn($apiResponse);

        $flightEvents = $provider->getFlightEvents('2024-09-12', 'BUE', 'BCN');

        // Verificar que no se retornan eventos
        $this->assertEmpty($flightEvents);
    }
}