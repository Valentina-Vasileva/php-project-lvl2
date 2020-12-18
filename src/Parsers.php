<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $pathTofile): object
{
    $extension = pathinfo($pathTofile, PATHINFO_EXTENSION);

    $data = file_get_contents($pathTofile) === false ? '' : file_get_contents($pathTofile);

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
