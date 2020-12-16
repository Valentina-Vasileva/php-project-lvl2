<?php

namespace Gendiff\Differ;

use function Funct\Strings\startsWith;
use function Gendiff\Parsers\parse;
use function Gendiff\Builder\buildDifference;
use function Gendiff\Formatters\format;

function getFullPath(string $file)
{
    if (!startsWith($file, "/")) {
        $cwd = getcwd();
        $fullPathToFile = "{$cwd}/{$file}";
    } else {
        $fullPathToFile = $file;
    }
    return $fullPathToFile;
}

function genDiff($firstFile, $secondFile, $formatName = 'stylish')
{
    $parsedFirstFile =  parse(getFullPath($firstFile));
    $parsedSecondFile = parse(getFullPath($secondFile));

    $differences = buildDifference($parsedFirstFile, $parsedSecondFile);
    $formatted = format($differences, $formatName);

    return $formatted;
}

function printDifference($firstFile, $secondFile, $formatName)
{
    print_r(genDiff($firstFile, $secondFile, $formatName));
}
