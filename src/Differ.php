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
        $fileExistenseException = new \Exception("The file '{$fullPathToFile}' doesn't exist\n");
        throw $fileExistenseException;
    }

    $data = file_get_contents($fullPathToFile) === false ? '' : file_get_contents($fullPathToFile);
    return $data;
}

function genDiff($firstPathToFile, $secondPathToFile, $formatName = 'stylish'): string
{
    try {
        $firstData = getDataFromFile($firstPathToFile);
        $secondData = getDataFromFile($secondPathToFile);
    } catch (\Exception $fileExistenseException) {
        echo $fileExistenseException->getMessage();
        return '';
    }

    try {
        $parsedFirstFile =  parse($firstData, pathinfo($firstPathToFile, PATHINFO_EXTENSION));
        $parsedSecondFile = parse($secondData, pathinfo($secondPathToFile, PATHINFO_EXTENSION));
    } catch (\Exception $extensionException) {
        echo $extensionException->getMessage();
        return '';
    }

    $differences = buildDiff($parsedFirstFile, $parsedSecondFile);

    try {
        $formattedDifferences = format($differences, $formatName);
    } catch (\Exception $formatException) {
        echo $formatException->getMessage();
        return '';
    }

    return $formattedDifferences;
}
