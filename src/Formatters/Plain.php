<?php

namespace Differ\Formatters\Plain;

function formatValue($value): string
{
    if ($value === false) {
        $formattedValue = 'false';
    } elseif ($value === null) {
        $formattedValue = 'null';
    } elseif ($value === true) {
        $formattedValue = 'true';
    } elseif (is_array($value) || is_object($value)) {
        $formattedValue = '[complex value]';
    } elseif (is_string($value)) {
        $formattedValue = "'$value'";
    } else {
        $formattedValue = $value;
    }
    return $formattedValue;
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
