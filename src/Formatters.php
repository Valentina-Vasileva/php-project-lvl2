<?php

namespace Gendiff\Formatters;

use function Funct\Strings\startsWith;
use function Gendiff\Formatters\Stylish\formatToStylish;

function format(object $data, $format)
{
    switch ($format) {
        case ('stylish'):
            $formattedData = formatToStylish($data);
            break;
    }

    return $formattedData;
}
