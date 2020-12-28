<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;

function stringify($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if ($value === null) {
        return 'null';
    }
    if (is_array($value) || is_object($value)) {
        return '[complex value]';
    }
    if (is_string($value)) {
        return "'{$value}'";
    }
    return "{$value}";
}

function formatToPlain(array $data, string $ancestry): string
{
    $formatted = array_map(function ($node) use ($ancestry) {

        $formattedOldValue = stringify($node['oldValue']);
        $formattedNewValue = stringify($node['newValue']);
        $property = "{$ancestry}{$node['key']}";

        if ($node["type"] === "added") {
            return "Property '{$property}' was added with value: {$formattedNewValue}";
        }
        if ($node["type"] === "deleted") {
            return "Property '{$property}' was removed";
        }
        if ($node["type"] === "changed") {
            return "Property '{$property}' was updated. From {$formattedOldValue} to {$formattedNewValue}";
        }
        if ($node["type"] === "complex") {
            return formatToPlain($node["children"], "{$property}.");
        }
        return [];
    }, $data);

    return implode("\n", flattenAll($formatted));
}

function format(array $data)
{
    return formatToPlain($data, '');
}
