<?php

namespace Differ\Formatters;

use function Funct\Strings\startsWith;
use function Differ\Formatters\Stylish\formatToStylish;
use function Differ\Formatters\Plain\formatToPlain;

function format(array $data, string $format): string
{
    switch ($format) {
        case (false):
            throw new \Exception("The report format '{$format}' is not supported");
        case ('stylish'):
            return formatToStylish($data);
        case ('plain'):
            return formatToPlain($data);
        case ('json'):
            $formattedData = json_encode($data);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception(json_last_error_msg());
            }
            return $formattedData;
        default:
            throw new \Exception("The report format '{$format}' is not supported");
    }
}
