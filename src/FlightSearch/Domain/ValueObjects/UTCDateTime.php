<?php

namespace Src\FlightSearch\Domain\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

readonly class UTCDateTime
{
    private DateTimeImmutable $dateTime;
    public function __construct(string $dateTime)
    {
        $this->dateTime = $this->ensureIsValid($dateTime);
    }

    /**
     * Ensures the provided datetime string is valid and creates a DateTimeImmutable object.
     *
     * @param string $dateTime The datetime string to validate and convert.
     * @return DateTimeImmutable The validated DateTimeImmutable object in UTC timezone.
     * @throws InvalidArgumentException If the provided datetime string is invalid.
     */
    private function ensureIsValid(string $dateTime): DateTimeImmutable
    {
        try {
            $date = new DateTimeImmutable($dateTime, new \DateTimeZone('UTC'));
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Invalid UTC datetime: $dateTime");
        }

        return $date;
    }

    public function value(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function equals(self $other): bool
    {
        return $this->dateTime->getTimestamp() === $other->value()->getTimestamp();
    }

    public function isBefore(self $other): bool
    {
        return $this->dateTime < $other->value();
    }

    public function isAfter(self $other): bool
    {
        return $this->dateTime > $other->value();
    }

}