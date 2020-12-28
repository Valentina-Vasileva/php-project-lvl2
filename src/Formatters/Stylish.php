<?php

namespace Differ\Formatters\Stylish;

function stringify($value, int $level): string
{
    if ($value === false) {
        return 'false';
    } elseif ($value === null) {
        return 'null';
    } elseif ($value === true) {
        return 'true';
    } elseif (is_object($value)) {
        $keys = array_keys(get_object_vars($value));

        $formattedValue = array_reduce($keys, function ($acc, $key) use ($value, $level) {
            $levelSpaces = str_repeat(" ", ($level + 1) * 4);
            $newAcc = "{$acc}{$levelSpaces}{$key}: " . stringify($value->$key, $level + 1) . "\n";
            return $newAcc;
        }, "{\n");

        return $formattedValue . str_repeat(" ", $level * 4) . "}";
    } elseif (is_array($value)) {
        return array_reduce($value, fn($acc, $item) => $acc . "{$item} ", "{ ") . "}";
    } else {
        return $value;
    }
}

function formatToStylish($data, $startSymbol = "{\n", $level = 1): string
{
    $formatted = array_reduce($data, function ($acc, $node) use ($level) {
        $levelSpaces = str_repeat(" ", ($level - 1) * 4);
        $formattedOldValue = stringify($node['oldValue'], $level);
        $formattedNewValue = stringify($node['newValue'], $level);

        $addedNode = "{$levelSpaces}  + {$node['key']}: {$formattedNewValue}\n";
        $deletedNode = "{$levelSpaces}  - {$node['key']}: {$formattedOldValue}\n";
        $unchangedNode = "{$levelSpaces}    {$node['key']}: {$formattedNewValue}\n";
        $complexNode = "{$levelSpaces}    {$node['key']}: {\n";

        if ($node["type"] === "added") {
            $newAcc = "{$acc}{$addedNode}";
        } elseif ($node["type"] === "deleted") {
            $newAcc = "{$acc}{$deletedNode}";
        } elseif ($node["type"] === "changed") {
            $newAcc = "{$acc}{$deletedNode}{$addedNode}";
        } elseif ($node["type"] === "unchanged") {
            $newAcc = "{$acc}{$unchangedNode}";
        } else {
            $newAcc = "{$acc}{$complexNode}" . formatToStylish($node["children"], "", $level + 1) . "\n";
        }
        return $newAcc;
    }, $startSymbol);

    return $formatted . str_repeat(" ", ($level - 1) * 4) . "}";
}
