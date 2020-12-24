<?php

namespace Differ\Differ;

use function Funct\Strings\startsWith;
use function Differ\Parsers\parse;
use function Differ\Builder\buildDiff;
use function Differ\Formatters\format;

function getDataFromFile(string $pathToFile): string
{
    if (!startsWith($pathToFile, "/")) {
        $cwd = getcwd();
        $fullPathToFile = "{$cwd}/{$pathToFile}";
    } else {
        $fullPathToFile = $pathToFile;
    }

    if (!file_exists($fullPathToFile)) {
        throw new \Exception("The file '{$fullPathToFile}' doesn't exist\n");
    }

    $data = file_get_contents($fullPathToFile) === false ? '' : file_get_contents($fullPathToFile);
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
