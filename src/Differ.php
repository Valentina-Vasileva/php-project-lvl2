<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Builder\buildDiff;
use function Differ\Formatters\format;

function getDataFromFile(string $pathToFile): string
{
    if (!is_readable($pathToFile)) {
        throw new \Exception("The file '{$pathToFile}' is not readable");
    }

    $data = file_get_contents($pathToFile);

    return $data;
}

function genDiff($filepath1, $filepath2, $formatName = 'stylish'): string
{
    $firstData = getDataFromFile($filepath1);
    $secondData = getDataFromFile($filepath2);

    $parsedFirstFile =  parse($firstData, pathinfo($filepath1, PATHINFO_EXTENSION));
    $parsedSecondFile = parse($secondData, pathinfo($filepath2, PATHINFO_EXTENSION));

    $differences = buildDiff($parsedFirstFile, $parsedSecondFile);

    $formattedDifferences = format($differences, $formatName);

    return $formattedDifferences;
}
