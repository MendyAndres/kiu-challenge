<?php
declare(strict_types=1);

namespace Src\FlightSearch\Domain\ValueObjects;

use InvalidArgumentException;

readonly class JourneyConstraints
{
    public function __construct(
        private int $maxDurationInHours,
        private int $maxConnections,
        private int $maxConnectionTimeInHours
    )
    {
        $this->ensureAreValid($maxDurationInHours, $maxConnections, $maxConnectionTimeInHours);
    }

    /**
     * Validates the input constraints to ensure they meet the required conditions.
     *
     * @param int $maxDurationInHours Maximum allowed duration in hours. Must be greater than 0.
     * @param int $maxConnections Maximum number of connections. Must be 0 or a positive integer.
     * @param int $maxConnectionTimeInHours Maximum connection time in hours. Must be greater than 0.
     * @return void
     * @throws InvalidArgumentException If any of the constraints are invalid.
     */
    private function ensureAreValid(int $maxDurationInHours, int $maxConnections, int $maxConnectionTimeInHours): void
    {
        if ($maxDurationInHours <= 0 || $maxConnections < 0 || $maxConnectionTimeInHours <= 0) {
            throw new InvalidArgumentException("Invalid constraints");
        }
    }

    public function maxDurationInHours(): int
    {
        return $this->maxDurationInHours;
    }

    public function maxConnections(): int
    {
        return $this->maxConnections;
    }

    public function maxConnectionTimeInHours(): int
    {
        return $this->maxConnectionTimeInHours;
    }

}