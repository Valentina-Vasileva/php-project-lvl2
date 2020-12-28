<?php

namespace Differ\Formatters\Stylish;

function stringify($value, int $level): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if ($value === null) {
        return 'null';
    }
    if (is_object($value)) {
        $keys = array_keys(get_object_vars($value));

        $formattedArrayOfValue = array_map(function ($key) use ($value, $level) {
            $levelSpaces = str_repeat(" ", ($level + 1) * 4);
            $childValue = stringify($value->$key, $level + 1);
            return "{$levelSpaces}{$key}: {$childValue}";
        }, $keys);

        $formattedValue = implode("\n", $formattedArrayOfValue);
        $levelStart = "{\n";
        $levelEnd = "\n" . str_repeat(" ", $level * 4) . "}";
        return "{$levelStart}{$formattedValue}{$levelEnd}";
    }
    if (is_array($value)) {
        return "[" . implode(", ", $value) . "]";
    }
    return "{$value}";
}

function formatToStylish($data, int $level): string
{
    $formattedArray = array_map(function ($node) use ($level) {

        $levelSpaces = str_repeat(" ", ($level - 1) * 4);

        $formattedOldValue = stringify($node['oldValue'], $level);
        $formattedNewValue = stringify($node['newValue'], $level);

        if ($node["type"] === "added") {
            return "{$levelSpaces}  + {$node['key']}: {$formattedNewValue}";
        }

        if ($node["type"] === "deleted") {
            return "{$levelSpaces}  - {$node['key']}: {$formattedOldValue}";
        }

        if ($node["type"] === "changed") {
            $addedNode = "{$levelSpaces}  + {$node['key']}: {$formattedNewValue}";
            $deletedNode = "{$levelSpaces}  - {$node['key']}: {$formattedOldValue}";
            return implode("\n", [$deletedNode, $addedNode]);
        }

        if ($node["type"] === "unchanged") {
            return "{$levelSpaces}    {$node['key']}: {$formattedNewValue}";
        }

        $children = formatToStylish($node["children"], $level + 1);
        return "{$levelSpaces}    {$node['key']}: {$children}";
    }, $data);

    $levelStart = "{\n";
    $levelEnd = "\n" . str_repeat(" ", ($level - 1) * 4) . "}";
    $formattedString = implode("\n", $formattedArray);
    return "{$levelStart}{$formattedString}{$levelEnd}";
}

function format(array $data)
{
    return formatToStylish($data, 1);
}
