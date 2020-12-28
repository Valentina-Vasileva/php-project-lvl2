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

function formatToPlain(array $data, $path = '', $startSymbols = ''): string
{
    $formatted = array_reduce($data, function ($acc, $node) use ($path) {

        $formattedOldValue = stringify($node['oldValue']);
        $formattedNewValue = stringify($node['newValue']);
        $pathToNode = "{$path}{$node['key']}";

        if ($node["type"] === "added") {
            $newAcc = $acc . "\nProperty '{$pathToNode}' was added with value: {$formattedNewValue}";
        } elseif ($node["type"] === "deleted") {
            $newAcc = $acc . "\nProperty '{$pathToNode}' was removed";
        } elseif ($node["type"] === "changed") {
            $newAcc = $acc
            . "\nProperty '{$pathToNode}' was updated. From {$formattedOldValue} to {$formattedNewValue}";
        } elseif ($node["type"] === "complex") {
            $newAcc = $acc . formatToPlain($node["children"], "{$pathToNode}.", "\n");
        } else {
            $newAcc = $acc;
        }
        return $newAcc;
    }, "");

    return substr($startSymbols . $formatted, 1);
}
