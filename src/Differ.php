<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Builder\buildDiff;
use function Differ\Formatters\format;

function getFileData(string $filepath1): string
{
    if (!is_readable($filepath1)) {
        throw new \Exception("The file '{$filepath1}' is not readable");
    }

    $data = file_get_contents($filepath1);

    return $data;
}

function genDiff($filepath1, $filepath2, $formatName = 'stylish'): string
{
    $firstData = getFileData($filepath1);
    $secondData = getFileData($filepath2);

    $parsedFirstFile =  parse($firstData, pathinfo($filepath1, PATHINFO_EXTENSION));
    $parsedSecondFile = parse($secondData, pathinfo($filepath2, PATHINFO_EXTENSION));

    $differences = buildDiff($parsedFirstFile, $parsedSecondFile);

    $formattedDifferences = format($differences, $formatName);

    return $formattedDifferences;
}
