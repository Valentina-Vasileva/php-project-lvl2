<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;

function stringify($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
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

        switch ($node["type"]) {
            case ("added"):
                return "Property '{$property}' was added with value: {$formattedNewValue}";
            case ("deleted"):
                return "Property '{$property}' was removed";
            case ("changed"):
                return "Property '{$property}' was updated. From {$formattedOldValue} to {$formattedNewValue}";
            case ("complex"):
                return formatToPlain($node["children"], "{$property}.");
            case ("unchanged"):
                return [];
            default:
                throw new \Exception("The node type '{$node['type']}' is not identified");
        }
    }, $data);

    return implode("\n", flattenAll($formatted));
}

function format(array $data): string
{
    return formatToPlain($data, '');
}
