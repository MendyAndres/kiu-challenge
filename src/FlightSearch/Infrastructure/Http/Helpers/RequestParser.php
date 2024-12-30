<?php

namespace Src\FlightSearch\Infrastructure\Http\Helpers;

class RequestParser
{
    public static function getParams(): array
    {
        return $_GET;
    }
}