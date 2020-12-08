<?php

namespace Gendiff\Differ;

use function Funct\Strings\startsWith;

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

    $firstFile = json_decode(file_get_contents(getFullPath($firstFile)), true);
    $secondFile = json_decode(file_get_contents(getFullPath($secondFile)), true);

    $keys = array_merge(array_keys($firstFile), array_keys($secondFile));
    sort($keys);
    $differences = array_reduce($keys, function ($acc, $key) use ($firstFile, $secondFile) {
        $keyUnmodified = "  {$key}";
        $keyAdded = "+ {$key}";
        $keyDeleted = "- {$key}";

        if (!array_key_exists($key, $firstFile)) {
            $acc[$keyAdded] = $secondFile[$key];
        } elseif (!array_key_exists($key, $secondFile)) {
            $acc[$keyDeleted] = $firstFile[$key];
        } elseif ($firstFile[$key] !== $secondFile[$key]) {
            if (!array_key_exists($keyDeleted, $acc)) {
                $acc[$keyDeleted] = $firstFile[$key];
            } else {
                $acc[$keyAdded] = $secondFile[$key];
            }
        } else {
            $acc[$keyUnmodified] = $firstFile[$key];
        }

        return $acc;
    }, []);

    print_r(formatResult($differences));
    return formatResult($differences);
}
