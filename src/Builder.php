<?php

namespace Differ\Builder;

use Illuminate\Support\Collection;

function getPropertiesNames(object $object): array
{
    return array_keys(get_object_vars($object));
}

function createNode(string $key, string $type, $children, $pastValue, $newValue): array
{
    return [
        "key" => $key,
        "type" => $type,
        "children" => $children,
        "pastValue" => $pastValue,
        "newValue" => $newValue
    ];
}

function buildDiff(object $firstData, object $secondData): array
{
    $keys = array_merge(getPropertiesNames($firstData), getPropertiesNames($secondData));
    $sortedKeys = collect($keys)->unique()->sort()->values()->all();

    $differences = array_reduce($sortedKeys, function ($acc, $key) use ($firstData, $secondData) {
        if (!property_exists($firstData, $key)) {
            $acc[] = createNode($key, "added", [], null, $secondData->$key);
        } elseif (!property_exists($secondData, $key)) {
            $acc[] = createNode($key, "deleted", [], $firstData->$key, null);
        } elseif ($firstData->$key !== $secondData->$key) {
            if (is_object($firstData->$key) && is_object($secondData->$key)) {
                $acc[] = createNode($key, "complex", buildDiff($firstData->$key, $secondData->$key), null, null);
            } else {
                $acc[] = createNode($key, "changed", [], $firstData->$key, $secondData->$key);
            }
        } else {
                $acc[] =  createNode($key, "unchanged", [], $firstData->$key, $secondData->$key);
        }
        return $acc;
    }, []);

    return  $differences;
}
