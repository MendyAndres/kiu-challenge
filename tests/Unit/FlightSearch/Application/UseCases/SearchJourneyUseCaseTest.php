<?php

namespace Tests\Unit\FlightSearch\Application\UseCases;

use PHPUnit\Framework\TestCase;
use Src\FlightSearch\Application\DTOs\JourneyDTO;
use Src\FlightSearch\Application\UseCases\SearchJourneyUseCase;
use Src\FlightSearch\Domain\Contracts\FlightEventsProviderInterface;
use Src\FlightSearch\Domain\Entities\FlightEvent;
use Src\FlightSearch\Domain\ValueObjects\CityCode;
use Src\FlightSearch\Domain\ValueObjects\FlightNumber;
use Src\FlightSearch\Domain\ValueObjects\JourneyConstraints;
use Src\FlightSearch\Domain\ValueObjects\UTCDateTime;

class SearchJourneyUseCaseTest extends TestCase
{
    /**
     * Tests the generation of valid journeys by simulating a flight event provider
     * and verifying that the generated journeys adhere to the specified constraints.
     *
     * @return void
     */
    public function testGenerateValidJourneys(): void
    {
        $flightEventProviderMock = $this->createMock(FlightEventsProviderInterface::class);

        $flightEvents = [
            new FlightEvent(
                new FlightNumber('XX1234'),
                new UTCDateTime('2024-09-12T12:00:00Z'),
                new UTCDateTime('2024-09-12T18:00:00Z'),
                new CityCode('BUE'),
                new CityCode('MAD')
            ),
            new FlightEvent(
                new FlightNumber('XX5678'),
                new UTCDateTime('2024-09-12T20:00:00Z'),
                new UTCDateTime('2024-09-12T23:00:00Z'),
                new CityCode('MAD'),
                new CityCode('BCN')
            ),
            new FlightEvent(
                new FlightNumber('XX5678'),
                new UTCDateTime('2024-09-13T10:00:00Z'), // Out of the allowed connection range
                new UTCDateTime('2024-09-13T12:00:00Z'),
                new CityCode('BUE'),
                new CityCode('BCN')
            )
        ];

        $flightEventProviderMock->method('getFlightEvents')
            ->willReturn($flightEvents);

        $useCase = new SearchJourneyUseCase($flightEventProviderMock);

        $constraints = new JourneyConstraints(24, 2, 4);

        $journeys = $useCase->execute('2024-09-12', 'BUE', 'BCN', $constraints);

        $this->assertCount(2, $journeys);
        $this->assertInstanceOf(JourneyDTO::class, $journeys[0]);
        $this->assertInstanceOf(JourneyDTO::class, $journeys[1]);
    }

    /**
     * Tests that no valid journeys are returned when the flight events do not satisfy the provided journey constraints.
     *
     * @return void
     */
    public function testNoValidJourneys(): void
    {
        $flightEventProviderMock = $this->createMock(FlightEventsProviderInterface::class);

        $flightEvents = [
            new FlightEvent(
                new FlightNumber('XX1234'),
                new UTCDateTime('2024-09-12T12:00:00Z'),
                new UTCDateTime('2024-09-12T18:00:00Z'),
                new CityCode('BUE'),
                new CityCode('MAD')
            ),
            new FlightEvent(
                new FlightNumber('XX5678'),
                new UTCDateTime('2024-09-13T10:00:00Z'), // Out of the allowed connection range
                new UTCDateTime('2024-09-13T12:00:00Z'),
                new CityCode('MAD'),
                new CityCode('BCN')
            ),
        ];

        $flightEventProviderMock->method('getFlightEvents')
            ->willReturn($flightEvents);

        $useCase = new SearchJourneyUseCase($flightEventProviderMock);

        $constraints = new JourneyConstraints(24, 2, 4);

        $journeys = $useCase->execute('2024-09-12', 'BUE', 'BCN', $constraints);

        $this->assertCount(0, $journeys);
    }

    /**
     * Tests the distinction between valid and invalid journeys based on the provided flight events.
     *
     * This method verifies the journey selection process by simulating various flight events,
     * some of which may be invalid due to factors such as insufficient connection time.
     * It ensures that only valid journeys are returned by the `SearchJourneyUseCase`.
     *
     * @return void
     */
    public function testValidAndInvalidJourneys(): void
    {
        $flightEventProviderMock = $this->createMock(FlightEventsProviderInterface::class);

        $flightEvents = [
            // Valid
            new FlightEvent(
                new FlightNumber('XX1234'),
                new UTCDateTime('2024-09-12T12:00:00Z'),
                new UTCDateTime('2024-09-12T18:00:00Z'),
                new CityCode('BUE'),
                new CityCode('MAD')
            ),
            // Invalid due to connection time
            new FlightEvent(
                new FlightNumber('XX5678'),
                new UTCDateTime('2024-09-13T10:00:00Z'),
                new UTCDateTime('2024-09-13T12:00:00Z'),
                new CityCode('MAD'),
                new CityCode('BCN')
            ),
            // Valid
            new FlightEvent(
                new FlightNumber('XX7890'),
                new UTCDateTime('2024-09-12T20:00:00Z'),
                new UTCDateTime('2024-09-12T23:00:00Z'),
                new CityCode('MAD'),
                new CityCode('BCN')
            )
        ];

        $flightEventProviderMock->method('getFlightEvents')
            ->willReturn($flightEvents);

        $useCase = new SearchJourneyUseCase($flightEventProviderMock);

        $journeys = $useCase->execute('2024-09-12', 'BUE', 'BCN');

        $this->assertCount(1, $journeys);
    }

}