<?php
declare(strict_types=1);

namespace Src\FlightSearch\Domain\ValueObjects;

use InvalidArgumentException;

readonly class FlightNumber
{
    public function __construct(private string $number)
    {
        $this->ensureIsValid($number);
    }

    /**
     * Ensures that the provided flight number is valid based on the required format of two uppercase letters followed by four digits.
     *
     * @param string $number The flight number to validate.
     * @return void
     * @throws InvalidArgumentException If the flight number does not match the required format.
     */
    private function ensureIsValid(string $number): void
    {
        if(!preg_match('/^[A-Z]{2}\d{4}$/', $number)) {
            throw new InvalidArgumentException("Invalid flight number: $number. Must be 2 uppercase letters followed by 3 digits");
        }
    }

    public function value(): string
    {
        return $this->number;
    }

    public function equals(self $other): bool
    {
        return $this->value() === $other->value();
    }
}