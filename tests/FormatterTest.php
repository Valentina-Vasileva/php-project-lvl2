<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use Gendiff\Formatter;

class FormatterTest extends TestCase
{

    protected $objectDataBefore;

    protected function setUp(): void
    {
        $docBefore = [
            "common" => [
                "+ follow" => false,
                "setting1" => "Value 1",
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

        $this->objectDataBefore = json_decode(json_encode($docBefore), false);
    }

    public function testFormatToStylish()
    {

        $docAfter = file_get_contents(__DIR__ . '/fixtures/Result1.txt');
        $this->assertEquals($docAfter, Formatter\formatToStylish($this->objectDataBefore));
    }

     /**
     * @dataProvider additionProvider
     */

    public function testFormat($expected, $format)
    {
        $this->assertEquals($expected, Formatter\format($this->objectDataBefore, $format));
    }

    public function additionProvider()
    {
        $docAfterStylish = file_get_contents(__DIR__ . '/fixtures/Result1.txt');
        return [
            [$docAfterStylish, 'stylish']
        ];
    }
}