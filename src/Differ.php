<?php

namespace Gendiff\Differ;

use function Funct\Strings\startsWith;
use function Gendiff\Parsers\parse;
use function Gendiff\Builder\buildDifference;
use function Gendiff\Formatter\format;

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


function getDifference($firstFile, $secondFile, $format = 'stylish')
{
    $parsedFirstFile =  parse(getFullPath($firstFile));
    $parsedSecondFile = parse(getFullPath($secondFile));

    $differences = buildDifference($parsedFirstFile, $parsedSecondFile);
    $formatted = format($differences, $format);

    print_r($formatted);
    return $formatted;
}
