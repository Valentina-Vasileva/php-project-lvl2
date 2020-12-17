<?php

namespace Gendiff\Builder;

use Illuminate\Support\Collection;

function getPropertiesNames(object $object)
{
    return array_keys(get_object_vars($object));
}

function buildDifference(object $firstData, object $secondData)
{
    $keys = array_merge(getPropertiesNames($firstData), getPropertiesNames($secondData));
    $sortedKeys = collect($keys)->sort()->values()->all();

    $differences = array_reduce($sortedKeys, function ($acc, $key) use ($firstData, $secondData) {

        $keyUnmodified = $key;
        $keyAdded = "+ {$key}";
        $keyDeleted = "- {$key}";

        if (!property_exists($firstData, $key)) {
            $acc->$keyAdded = $secondData->$key;
        } elseif (!property_exists($secondData, $key)) {
            $acc->$keyDeleted = $firstData->$key;
        } elseif ($firstData->$key !== $secondData->$key) {
            if (is_object($firstData->$key) && is_object($secondData->$key)) {
                $acc->$keyUnmodified = buildDifference($firstData->$key, $secondData->$key);
            } else {
                if (!property_exists($acc, $keyDeleted)) {
                    $acc->$keyDeleted = $firstData->$key;
                } else {
                    $acc->$keyAdded = $secondData->$key;
                }
            }
        } else {
            if (is_object($firstData->$key) && is_object($secondData->$key)) {
                $acc->$keyUnmodified = buildDifference($firstData->$key, $secondData->$key);
            } else {
                $acc->$keyUnmodified = $firstData->$key;
            }
        }
        return $acc;
    }, new \stdClass());

    return  $differences;
}
