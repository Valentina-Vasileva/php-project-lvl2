<?php

namespace Differ\Differ;

use function Funct\Strings\startsWith;
use function Differ\Parsers\parse;
use function Differ\Builder\buildDifference;
use function Differ\Formatters\format;

function getFullPath(string $file): string
{
    if (!startsWith($file, "/")) {
        $cwd = getcwd();
        $fullPathToFile = "{$cwd}/{$file}";
    } else {
        $fullPathToFile = $file;
    }
    return $fullPathToFile;
}

function genDiff($firstPathToFile, $secondPathToFile, $formatName = 'stylish'): string
{
    $parsedFirstFile =  parse(getFullPath($firstPathToFile));
    $parsedSecondFile = parse(getFullPath($secondPathToFile));

    $differences = buildDifference($parsedFirstFile, $parsedSecondFile);
    $formatted = format($differences, $formatName);

    return $formatted;
}
