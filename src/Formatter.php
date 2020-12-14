<?php

namespace Gendiff\Formatter;

use function Funct\Strings\startsWith;

function formatToStylish(object $data, $spaces = '', $startSymbol = "{\n", $level = 1)
{
    $keys = array_keys((array) $data);
    $formatted = array_reduce($keys, function ($acc, $key) use ($data, $level) {

        if ($data->$key === false) {
            $formattedValue = 'false';
        } elseif($data->$key === null) {
            $formattedValue = 'null';
        } elseif($data->$key === true) {
            $formattedValue = 'true';
        } else {
            $formattedValue = $data->$key;
        }
        
        if (startsWith($key, '+') || startsWith($key, '-')) {
            $newSpaces = str_repeat(" ", ($level-1) * 4 + 2); 
        } else {
            $newSpaces = str_repeat(" ", $level * 4);
        }
        
        if (is_object($data->$key)) {
            $acc = $acc . "{$newSpaces}{$key}: {\n";
            $acc = $acc . formatToStylish($data->$key, $newSpaces, $startSymbol = "", $level+1);
        } else {
            $acc = $acc . "{$newSpaces}{$key}: {$formattedValue}\n";
        }
        return $acc;
    }, $startSymbol);
    $spaces = str_repeat(" ", ($level-1)*4); 
    $formatted = $formatted . "{$spaces}}\n";
    return $formatted;
}


function format(object $data, $format)
{
    switch ($format) {
        case ('stylish'):
            $formattedData = formatToStylish($data);
            break;
    }
    
    return $formattedData;
}