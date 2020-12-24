<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $data, string $format): object
{
    switch ($format) {
        case ('json'):
            $parsedData = json_decode($data, false);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception(json_last_error_msg());
            }
            return $parsedData;
        case ('yaml'):
        case ('yml'):
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("The file format '{$format}' is not supported");
    }
}
