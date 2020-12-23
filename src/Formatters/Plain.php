<?php

namespace Differ\Formatters\Plain;

function formatValue($value): string
{
    if ($value === false) {
        return 'false';
    } elseif ($value === null) {
        return 'null';
    } elseif ($value === true) {
        return 'true';
    } elseif (is_array($value) || is_object($value)) {
        return '[complex value]';
    } elseif (is_string($value)) {
        return "'$value'";
    } else {
        return $value;
    }
}

function formatToPlain(array $data, $path = '', $startSymbols = ''): string
{
    $formatted = array_reduce($data, function ($acc, $node) use ($path) {

        $formattedPastValue = formatValue($node['pastValue']);
        $formattedNewValue = formatValue($node['newValue']);
        $pathToNode = "{$path}{$node['key']}";

        if ($node["type"] === "added") {
            $newAcc = $acc . "\nProperty '{$pathToNode}' was added with value: {$formattedNewValue}";
        } elseif ($node["type"] === "deleted") {
            $newAcc = $acc . "\nProperty '{$pathToNode}' was removed";
        } elseif ($node["type"] === "changed") {
            $newAcc = $acc
            . "\nProperty '{$pathToNode}' was updated. From {$formattedPastValue} to {$formattedNewValue}";
        } elseif ($node["type"] === "complex") {
            $newAcc = $acc . formatToPlain($node["children"], "{$pathToNode}.", "\n");
        } else {
            $newAcc = $acc;
        }
        return $newAcc;
    }, "");

    return substr($startSymbols . $formatted, 1);
}
