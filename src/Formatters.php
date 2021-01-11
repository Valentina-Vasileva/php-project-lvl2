<?php

namespace Differ\Formatters;

use Differ\Formatters\Stylish;
use Differ\Formatters\Plain;

function format(array $data, string $format): string
{
    switch ($format) {
        case ('stylish'):
            return Stylish\format($data);
        case ('plain'):
            return Plain\format($data);
        case ('json'):
            return json_encode($data, JSON_THROW_ON_ERROR);
        default:
            throw new \Exception("The report format '{$format}' is not supported");
    }
}
