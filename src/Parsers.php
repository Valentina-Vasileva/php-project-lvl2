<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $data, string $extension): object
{
    switch ($extension) {
        case ('json'):
            return json_decode($data, false);
        case ('yaml'):
            return Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("The extension '{$extension}' is not supported");
    }
}
