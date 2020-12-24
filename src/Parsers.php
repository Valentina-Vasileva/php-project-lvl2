<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $data, string $format): object
{
    switch ($format) {
        case ('json'):
            return json_decode($data, false);
        case ('yaml'):
        case ('yml'):
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("The file format '{$format}' is not supported");
    }
}
