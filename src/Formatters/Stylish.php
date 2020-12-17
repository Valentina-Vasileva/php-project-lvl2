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
            $newLevel = "{$newSpaces}{$key}: {\n";
            $newAcc = $acc . $newLevel . formatToStylish($data->$key, $newSpaces, "", $level + 1) . "\n";
        } else {
            $formattedValue = formatValue($data->$key);
            $newAcc = $acc . "{$newSpaces}{$key}: {$formattedValue}\n";
        }
        return $newAcc;
    }, $startSymbol);

    return $formatted . str_repeat(" ", ($level - 1) * 4) . "}";
}
