<?php

namespace Differ\Builder;

use function Funct\Collection\union;
use function Funct\Collection\sortBy;

function getPropertiesNames(object $object): array
{
    return array_keys(get_object_vars($object));
}

function createNode(string $key, string $type, $oldValue, $newValue, $children = null): array
{
    return [
        "key" => $key,
        "type" => $type,
        "oldValue" => $oldValue,
        "newValue" => $newValue,
        "children" => $children
    ];
}

function buildDiff(object $firstData, object $secondData): array
{
    $keys = union(getPropertiesNames($firstData), getPropertiesNames($secondData));
    $sortedKeys = array_values(sortBy($keys, fn($key) => $key));

    $differences = array_map(function ($key) use ($firstData, $secondData) {
        if (!property_exists($firstData, $key)) {
            return createNode($key, "added", null, $secondData->$key);
        }
        
        if (!property_exists($secondData, $key)) {
            return createNode($key, "deleted", $firstData->$key, null);
        }

        if (is_object($firstData->$key) && is_object($secondData->$key)) {
            return createNode($key, "complex", null, null, buildDiff($firstData->$key, $secondData->$key));
        }

        if ($firstData->$key !== $secondData->$key) {
            return createNode($key, "changed", $firstData->$key, $secondData->$key);
        }

        return createNode($key, "unchanged", $firstData->$key, $secondData->$key);
    }, $sortedKeys);

    return $differences;
}
