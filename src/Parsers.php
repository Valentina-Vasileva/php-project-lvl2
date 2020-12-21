<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $data, string $extension): object
{
    switch ($extension) {
        case ('json'):
            $parsed = json_decode($data, false);
            break;
        case ('yaml'):
            $parsed = Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            $parsed = new \stdClass();
    }
    return $parsed;
}
