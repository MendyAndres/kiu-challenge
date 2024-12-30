<?php
declare(strict_types=1);

namespace Src\FlightSearch\Application\DTOs;

class JourneyDTO
{
    public function __construct(
        public int $connections,
        public array $path
    ){}

    /**
     * Converts an array of flight event DTOs into an instance of the JourneyDTO class.
     *
     * @param array $flightEventDtos An array of flight event data transfer objects.
     * @return self An instance of the class containing the processed flight event data.
     */
    public static function toDto(array $flightEventDtos): self
    {
        return new self(
          count($flightEventDtos),
          $flightEventDtos
        );
    }
}