<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use Gendiff\Formatters\Stylish;

class StylishTest extends TestCase
{
    /**
    * @dataProvider additionProvider
    */

    public function testFormatValue($expected, $value)
    {
        $this->assertEquals($expected, Stylish\formatValue($value));
    }

    public function additionProvider()
    {
        $item = 'smth';

        return [
            ['false', false],
            ['true', true],
            ['null', null],
            ['{ 1 2 3 }', [1, 2, 3]],
            [1, 1]
        ];
    }

    public function testFormatToStylish()
    {

        $dataBefore = [
            "common" => [
                "+ follow" => false,
                "- setting1" => null,
                "+ setting1" => "",
                "- setting2" => 200,
                "- setting3" => true,
                "+ setting3" => null,
                "+ setting4" => "blah blah",
                "+ setting5" => [
                    "key5" => "value5"
                ],
                "setting6" => [
                    "doge" => [
                        "- wow" => '',
                        "+ wow" => "so much",
                    ],
                    "key" => "value",
                    "+ ops" => "vops"
                ],
            ],
            "group1" => [
                "- baz" => "bas",
                "+ baz" => "bars",
                "foo" => "bar",
                "- nest" => [
                    "key" => "value"
                ],
                "+ nest" => "str"
            ],
            "- group2" => [
                "abc" => 12345,
                "deep" => [
                    "id" => 45
                ]
            ],
            "+ group3" => [
                "fee" => 100500,
                "deep" => [
                    "id" => [
                        "number" => 45
                    ]
                ]
            ]
        ];

        $objectDataBefore = json_decode(json_encode($dataBefore), false);

        $docAfter = file_get_contents(__DIR__ . '/fixtures/ResultStylish.txt');
        $this->assertEquals($docAfter, Stylish\formatToStylish($objectDataBefore));
    }
}
