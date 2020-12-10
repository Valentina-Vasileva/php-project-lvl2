<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use Gendiff\Parsers;

class ParsersTest extends TestCase
{
    public function testParse()
    {
        $pathToJsonFile = __DIR__ . '/fixtures/TestDoc1.json';
        $pathToYamlFile = __DIR__ . '/fixtures/TestDoc1.yaml';
        $arrayOfData = [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];
        $expected = (object) $arrayOfData;
        $this->assertEquals($expected, parse($pathToJsonFile));
        $this->assertEquals($expected, parse($pathToYamlFile));
    }
}
