<?php

namespace Differ\Formatters;

use function Funct\Strings\startsWith;
use function Differ\Formatters\Stylish\formatToStylish;
use function Differ\Formatters\Plain\formatToPlain;

function format(object $data, $format): string
{
    switch ($format) {
        case ('stylish'):
            $formattedData = formatToStylish($data);
            break;
        case ('plain'):
            $formattedData = formatToPlain($data);
            break;
        case ('json'):
            $formattedData = json_encode($data);
            break;
        default:
            $formattedData = null;
    }

    return $formattedData;
}
