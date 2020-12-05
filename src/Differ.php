<?php

namespace Gendiff\Differ;

use function Funct\Strings\startsWith;
use function Funct\Collection\rest;
use function Funct\Collection\forEvery;

function getDifference($argv)
{
    $files = rest($argv);
    
    $fullPathsToFiles = forEvery($files, function($file) {
        if (!startsWith($file, "/")) {
            $cwd = getcwd();
            $fullPathToFile = "{$cwd}/{$file}";
        } else {
            $fullPathToFile = $file;
        }
        return $fullPathToFile;
    });

    $firstFile = json_decode(file_get_contents($fullPathsToFiles[0]), true);
    $secondFile = json_decode(file_get_contents($fullPathsToFiles[1]), true);

    $keys = array_merge(array_keys($firstFile), array_keys($secondFile));
    sort($keys);
    $differences = array_reduce($keys, function($acc, $key) use ($firstFile, $secondFile) {
        
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
            $acc[$key] = $firstFile[$key];
        }

        return $acc;
    }, []);
    
    print_r(json_encode($differences));
}