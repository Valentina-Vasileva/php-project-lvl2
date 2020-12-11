<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use Gendiff\Differ;

class DifferTest extends TestCase
{
    public function testGetFullPath()
    {
        $fullPath = '/home/User/TestDir/Doc.json';
        $incompletePath = 'Doc.json';
        $pwd = getcwd();
        $this->assertEquals($fullPath, Differ\getFullPath($fullPath));
        $this->assertEquals("{$pwd}/{$incompletePath}", Differ\getFullPath($incompletePath));
    }

    public function testFormatResult()
    {
        $docBefore = [
            "  common" => [
                "+ follow" => false,
                "  setting1" => "Value 1",
                "- setting2" => 200,
                "- setting3" => true,
                "+ setting3" => null,
                "+ setting4" => "blah blah",
                "+ setting5" => [
                    "key5" => "value5"
                ],
                "  setting6" => [
                    "  doge" => [
                        "- wow" => null,
                        "+ wow" => "so much",
                    ],
                    "  key" => "value",
                    "+ ops" => "vops"
                ],
            ],
            "group1" => [
                "- baz" => "bas",
                "+ baz" => "bars",
                "  foo" => "bar",
                "- nest" => [
                    "  key" => "value"
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
        $docAfter = file_get_contents(__DIR__ . '/fixtures/Result1.txt');
        $this->assertEquals($docAfter, Differ\formatResult($docBefore));
    }

    /**
     * @dataProvider additionProvider
     */

    public function testGetDifference($expected, $firstFile, $secondFile)
    {
        $this->assertEquals($expected, Differ\getDifference($firstFile, $secondFile));
    }

    public function additionProvider()
    {
        $docAfter = file_get_contents(__DIR__ . '/fixtures/Result1.txt');
        return [
            [$docAfter,  __DIR__ . '/fixtures/TestDoc1.json', __DIR__ . '/fixtures/TestDoc2.json'],
            [$docAfter,  __DIR__ . '/fixtures/TestDoc1.yaml', __DIR__ . '/fixtures/TestDoc2.yaml']
        ];
    }

}
