<?php

namespace Gendiff\Formatters;

use function Funct\Strings\startsWith;
use function Gendiff\Formatters\Stylish\formatToStylish;
use function Gendiff\Formatters\Plain\formatToPlain;

function format(object $data, $format)
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
    }

    return $formattedData;
}
