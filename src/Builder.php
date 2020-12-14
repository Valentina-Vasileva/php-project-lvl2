<?php

namespace Gendiff\Builder;

use Illuminate\Support\Collection;

function buildDifference(object $firstData, object $secondData)
{
    $firstDataAsArray = (array) $firstData;
    $secondDataAsArray = (array) $secondData;

    $keys = array_merge(array_keys($firstDataAsArray), array_keys($secondDataAsArray));
    $sortedKeys = collect($keys)->sort()->values()->all();

    $differences = array_reduce($sortedKeys, function ($acc, $key) use ($firstDataAsArray, $secondDataAsArray, $firstData, $secondData) {
       
        $keyUnmodified = $key;
        $keyAdded = "+ {$key}";
        $keyDeleted = "- {$key}";

        if (!array_key_exists($key, $firstDataAsArray)) {
            $acc[$keyAdded] = $secondDataAsArray[$key];
        } elseif (!array_key_exists($key, $secondDataAsArray)) {
            $acc[$keyDeleted] = $firstDataAsArray[$key];
        } elseif ($firstDataAsArray[$key] !== $secondDataAsArray[$key]) {
            if (is_object($firstData->$key) && is_object($secondData->$key)) {
                $acc[$keyUnmodified] = (array) buildDifference($firstData->$key, $secondData->$key);
            } else {
                if (!array_key_exists($keyDeleted, $acc)) {
                    $acc[$keyDeleted] = $firstDataAsArray[$key];
                } else {
                    $acc[$keyAdded] = $secondDataAsArray[$key];
                }
            }
        } else {
            if (is_object($firstData->$key) && is_object($secondData->$key)) {
                $acc[$keyUnmodified] = (array) buildDifference($firstData->$key, $secondData->$key);
            } else {
                $acc[$keyUnmodified] = $firstDataAsArray[$key];
            }
        }

        return $acc;

    }, []);
    return  json_decode(json_encode($differences), false);
}
