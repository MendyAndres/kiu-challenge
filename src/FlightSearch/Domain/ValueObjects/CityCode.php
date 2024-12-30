<?php
declare(strict_types=1);

namespace Src\FlightSearch\Domain\ValueObjects;


class CityCode
{
    public function __construct(private string $code)
    {
        $this->ensureIsValid($code);
    }

    /**
     * Validates that the provided code matches the required format.
     *
     * @param string $code The code to validate, expected to be exactly 3 uppercase letters.
     * @return void This method does not return a value but throws an exception if validation fails.
     * @throws \InvalidArgumentException If the code does not match the required format.
     */
    private function ensureIsValid(string $code): void
    {
        if(!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new \InvalidArgumentException("Invalid city code: $code. Must be 3 uppercase letters");
        }
    }

    public function value(): string
    {
        return $this->code;
    }

    public function equals(self $other): bool
    {
        return $this->value() === $other->value();
    }
}
