<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Parsers\parse;

class ParsersTest extends TestCase
{
    public function testParse()
    {
        $pathToJsonFile = __DIR__ . '/fixtures/TestDoc1.json';
        $pathToYamlFile = __DIR__ . '/fixtures/TestDoc1.yaml';
        
        $arrayOfData = [
            "common" => [
              "setting1" => null,
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
      
        $expected = json_decode(json_encode($arrayOfData), false);
        $this->assertEquals($expected, parse($pathToJsonFile));
        $this->assertEquals($expected, parse($pathToYamlFile));
    }
}
