<?php

namespace Differ\Formatters\Plain;

use function Funct\Strings\startsWith;

function formatValue($value)
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

function formatToPlain(object $data, $path = '', $startSymbols = '')
{
    $keys = array_keys(get_object_vars($data));

    $formatted = array_reduce($keys, function ($acc, $key) use ($data, $path, $keys) {

        $trimmedKey = trim($key, " /+/-");
        $addedKey = "+ {$trimmedKey}";
        $deletedKey = "- {$trimmedKey}";

        if (startsWith($key, '-')) {
            $acc = property_exists($data, $addedKey) ? $acc : $acc . "\nProperty '{$path}{$trimmedKey}' was removed";
        } elseif (startsWith($key, '+')) {
            if (property_exists($data, $deletedKey)) {
                $fromValue = formatValue($data->$deletedKey);
                $toValue = formatValue($data->$key);
                $acc = $acc . "\nProperty '{$path}{$trimmedKey}' was updated. From {$fromValue} to {$toValue}";
            } else {
                $valueAdded = formatValue($data->$key);
                $acc = $acc . "\nProperty '{$path}{$trimmedKey}' was added with value: {$valueAdded}";
            }
        } else {
            $acc = is_object($data->$key) ? $acc . formatToPlain($data->$key, "{$path}{$key}.", "\n") : $acc;
        }
        return $acc;
    }, "");

    return substr($startSymbols . $formatted, 1);
}
