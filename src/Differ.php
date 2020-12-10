<?php

namespace Gendiff\Differ;

use function Funct\Strings\startsWith;
use function Gendiff\Parsers\parse;

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


function formatResult(array $array)
{
    $jsonEncoded = json_encode($array);
    $droppedQuotes = preg_replace('/"/', "", $jsonEncoded);
    $addedLineBreaks = preg_replace('/}/', "\n$0\n", preg_replace(['/{/', '/,/'], "$0\n", $droppedQuotes));
    return $addedLineBreaks;
}

function getDifference($firstFile, $secondFile)
{
    $parsedFirstFile = (array) parse($firstFile);
    $parsedSecondFile = (array) parse($secondFile);
    $keys = array_merge(array_keys($parsedFirstFile), array_keys($parsedSecondFile));
    sort($keys);
    $differences = array_reduce($keys, function ($acc, $key) use ($parsedFirstFile, $parsedSecondFile) {
        $keyUnmodified = "  {$key}";
        $keyAdded = "+ {$key}";
        $keyDeleted = "- {$key}";

        if (!array_key_exists($key, $parsedFirstFile)) {
            $acc[$keyAdded] = $parsedSecondFile[$key];
        } elseif (!array_key_exists($key, $parsedSecondFile)) {
            $acc[$keyDeleted] = $parsedFirstFile[$key];
        } elseif ($parsedFirstFile[$key] !== $parsedSecondFile[$key]) {
            if (!array_key_exists($keyDeleted, $acc)) {
                $acc[$keyDeleted] = $parsedFirstFile[$key];
            } else {
                $acc[$keyAdded] = $parsedSecondFile[$key];
            }
        } else {
            $acc[$keyUnmodified] = $parsedFirstFile[$key];
        }

        return $acc;
    }, []);

    print_r(formatResult($differences));
    return formatResult($differences);
}
