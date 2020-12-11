<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function Gendiff\Parsers\parse;

class ParsersTest extends TestCase
{
    public function testParse()
    {
        $pathToJsonFile = __DIR__ . '/fixtures/TestDoc1.json';
        $pathToYamlFile = __DIR__ . '/fixtures/TestDoc1.yaml';
        $arrayOfData = [
            "common" => [
              "setting1" => "Value 1",
              "setting2" => 200,
              "setting3" => true,
              "setting6" => [
                "key" => "value",
                "doge" => [
                  "wow" => ""
                ]
              ]
            ],
            "group1" => [
              "baz" => "bas",
              "foo" => "bar",
              "nest" => [
                "key" => "value"
              ]
            ],
            "group2" => [
              "abc" => 12345,
              "deep" => [
                "id" => 45
              ]
            ]
        ];
      
        $expected = (object) $arrayOfData;
        $this->assertEquals($expected, parse($pathToJsonFile));
        $this->assertEquals($expected, parse($pathToYamlFile));
    }
}
