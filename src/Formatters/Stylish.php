<?php

namespace Differ\Formatters\Stylish;

function stringify($value, int $level): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_object($value)) {
        $keys = array_keys(get_object_vars($value));

        $formattedArrayOfValue = array_map(function ($key) use ($value, $level) {
            $indent = str_repeat(" ", ($level + 1) * 4);
            $childValue = stringify($value->$key, $level + 1);
            return "{$indent}{$key}: {$childValue}";
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

        $indent = str_repeat(" ", ($level - 1) * 4);

        $formattedOldValue = stringify($node['oldValue'], $level);
        $formattedNewValue = stringify($node['newValue'], $level);

        switch ($node["type"]) {
            case ("added"):
                return "{$indent}  + {$node['key']}: {$formattedNewValue}";
            case ("deleted"):
                return "{$indent}  - {$node['key']}: {$formattedOldValue}";
            case ("changed"):
                $addedNode = "{$indent}  + {$node['key']}: {$formattedNewValue}";
                $deletedNode = "{$indent}  - {$node['key']}: {$formattedOldValue}";
                return implode("\n", [$deletedNode, $addedNode]);
            case ("complex"):
                $children = formatToStylish($node["children"], $level + 1);
                return "{$indent}    {$node['key']}: {$children}";
            case ("unchanged"):
                return "{$indent}    {$node['key']}: {$formattedNewValue}";
            default:
                throw new \Exception("The node type '{$node['type']}' is not identified");
        }
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
