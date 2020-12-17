<?php

namespace Differ\Formatters\Stylish;

use function Funct\Strings\startsWith;

function formatValue($value): string
{
    if ($value === false) {
        $formattedValue = 'false';
    } elseif ($value === null) {
        $formattedValue = 'null';
    } elseif ($value === true) {
        $formattedValue = 'true';
    } elseif (is_array($value)) {
        $formattedValue = array_reduce($value, fn($acc, $item) => $acc . "{$item} ", "{ ") . "}";
    } else {
        $formattedValue = $value;
    }

    return $formattedValue;
}

function formatToStylish(object $data, $spaces = '', $startSymbol = "{\n", $level = 1): string
{
        $keys = array_keys(get_object_vars($data));
        $formatted = array_reduce($keys, function ($acc, $key) use ($data, $level) {

            if (startsWith($key, '+') || startsWith($key, '-')) {
                $newSpaces = str_repeat(" ", ($level - 1) * 4 + 2);
            } else {
                $newSpaces = str_repeat(" ", $level * 4);
            }
            if (is_object($data->$key)) {
                $acc = $acc . "{$newSpaces}{$key}: {\n";
                $acc = $acc . formatToStylish($data->$key, $newSpaces, $startSymbol = "", $level + 1) . "\n";
            } else {
                $formattedValue = formatValue($data->$key);
                $acc = $acc . "{$newSpaces}{$key}: {$formattedValue}\n";
            }
            return $acc;
        }, $startSymbol);

        $spaces = str_repeat(" ", ($level - 1) * 4);
        $formatted = $formatted . "{$spaces}}";
        return $formatted;
}
