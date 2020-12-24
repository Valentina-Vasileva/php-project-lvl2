<?php

namespace Differ\Builder;

use function Funct\Collection\union;
use function Funct\Collection\sortBy;

function getPropertiesNames(object $object): array
{
    return array_keys(get_object_vars($object));
}

function createNode(string $key, string $type, $children, $oldValue, $newValue): array
{
    return [
        "key" => $key,
        "type" => $type,
        "children" => $children,
        "oldValue" => $oldValue,
        "newValue" => $newValue
    ];
}

function buildDiff(object $firstData, object $secondData): array
{
    $keys = union(getPropertiesNames($firstData), getPropertiesNames($secondData));
    $sortedKeys = array_values(sortBy($keys, fn($key) => $key));

    $differences = array_map(function ($key) use ($firstData, $secondData) {
        if (!property_exists($firstData, $key)) {
            return createNode($key, "added", [], null, $secondData->$key);
        }
        if (!property_exists($secondData, $key)) {
            return createNode($key, "deleted", [], $firstData->$key, null);
        }
        if ($firstData->$key !== $secondData->$key) {
            if (is_object($firstData->$key) && is_object($secondData->$key)) {
                return createNode($key, "complex", buildDiff($firstData->$key, $secondData->$key), null, null);
            }
                return createNode($key, "changed", [], $firstData->$key, $secondData->$key);
        }
            return createNode($key, "unchanged", [], $firstData->$key, $secondData->$key);
    }, $sortedKeys);

    return $differences;
}
