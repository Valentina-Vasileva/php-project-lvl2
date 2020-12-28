<?php

namespace Differ\Formatters\Plain;

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

function formatToPlain(array $data, string $ancestry = '', string $startSymbols = ''): string
{
    $formatted = array_reduce($data, function ($acc, $node) use ($ancestry) {

        $formattedOldValue = stringify($node['oldValue']);
        $formattedNewValue = stringify($node['newValue']);
        $property = "{$ancestry}{$node['key']}";

        if ($node["type"] === "added") {
            $newAcc = $acc . "\nProperty '{$property}' was added with value: {$formattedNewValue}";
        } elseif ($node["type"] === "deleted") {
            $newAcc = $acc . "\nProperty '{$property}' was removed";
        } elseif ($node["type"] === "changed") {
            $newAcc = $acc
            . "\nProperty '{$property}' was updated. From {$formattedOldValue} to {$formattedNewValue}";
        } elseif ($node["type"] === "complex") {
            $newAcc = $acc . formatToPlain($node["children"], "{$property}.", "\n");
        } else {
            $newAcc = $acc;
        }
        return $newAcc;
    }, "");

    return substr($startSymbols . $formatted, 1);
}

function format(array $data)
{
    return formatToPlain($data);
}
