<?php

namespace Differ\Formatters;

use function Funct\Strings\startsWith;
use function Differ\Formatters\Stylish\formatToStylish;
use function Differ\Formatters\Plain\formatToPlain;

function format(array $data, string $format): string
{
    switch ($format) {
        case (false):
            $formattedData = '';
            break;
        case ('stylish'):
            $formattedData = formatToStylish($data);
            break;
        case ('plain'):
            $formattedData = formatToPlain($data);
            break;
        case ('json'):
            $formattedData = json_encode($data) === false ? '' : json_encode($data);
            break;
        default:
            $formattedData = '';
    }

    return $formattedData;
}
