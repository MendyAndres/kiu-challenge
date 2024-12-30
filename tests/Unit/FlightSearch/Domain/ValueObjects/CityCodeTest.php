<?php

namespace Tests\Unit\FlightSearch\Domain\ValueObjects;
use Src\FlightSearch\Domain\ValueObjects\CityCode;
use PHPUnit\Framework\TestCase;

class CityCodeTest extends TestCase
{
    public function tesValidCityCode(): void
    {
        $cityCode = new CityCode('BUE');
        $this->assertEquals('BUE', $cityCode->value());
    }

    public function testInvalidCityCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new CityCode('Buenos Aires');
    }

    /**
     * Tests the equality functionality of the CityCode class by comparing different instances.
     *
     * @return void
     */
    public function testCityCodeEquality(): void
    {
        $cityCode1 = new CityCode('BUE');
        $cityCode2 = new CityCode('BUE');
        $cityCode3 = new CityCode('MAD');
        $this->assertTrue($cityCode1->equals($cityCode2));
        $this->assertFalse($cityCode1->equals($cityCode3));
    }
}