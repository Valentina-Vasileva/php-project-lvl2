<?php

namespace Differ\Differ;

use function Funct\Strings\startsWith;
use function Differ\Parsers\parse;
use function Differ\Builder\buildDiff;
use function Differ\Formatters\format;

function getDataFromFile(string $pathToFile): string
{
    if (!file_exists($pathToFile)) {
        throw new \Exception("The file '{$pathToFile}' doesn't exist");
    }

    $data = file_get_contents($pathToFile) === false ? '' : file_get_contents($pathToFile);
    return $data;
}

function genDiff($firstPathToFile, $secondPathToFile, $formatName = 'stylish'): string
{
    $firstData = getDataFromFile($firstPathToFile);
    $secondData = getDataFromFile($secondPathToFile);

    $parsedFirstFile =  parse($firstData, pathinfo($firstPathToFile, PATHINFO_EXTENSION));
    $parsedSecondFile = parse($secondData, pathinfo($secondPathToFile, PATHINFO_EXTENSION));

    $differences = buildDiff($parsedFirstFile, $parsedSecondFile);

    $formattedDifferences = format($differences, $formatName);

    return $formattedDifferences;
}
