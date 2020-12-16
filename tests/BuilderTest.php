<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use Gendiff\Builder;

class BuilderTest extends TestCase
{
    public function testGetPropertiesNames()
    {
        $object = new \stdClass;
        $object->one = 'testone';
        $object->two = 2;
        $expected = ['one', 'two'];
        $this->assertEquals($expected, Builder\getPropertiesNames($object));
    }
    
    public function testBuildDifference()
    {
        $firstData = [
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

        $secondData = [
            "common" => [
            "follow" => false,
            "setting1" => "Value 1",
            "setting3" => null,
            "setting4" => "blah blah",
            "setting5" => [
                "key5" => "value5"
            ],
            "setting6" => [
                    "key" => "value",
                    "ops" => "vops",
                    "doge" => [
                        "wow" => "so much"
                    ]
                ]
            ],
            "group1" => [
                "foo" => "bar",
                "baz" => "bars",
                "nest" => "str"
            ],
            "group3" => [
                "fee" => 100500,
                "deep" => [
                    "id" => [
                        "number" => 45
                    ]
                ]
            ]
        ];

        $expected = [
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
                        "- wow" => null,
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
        $firstObject = json_decode(json_encode($firstData), false);
        $secondObject = json_decode(json_encode($secondData), false);
        $objectExpected = json_decode(json_encode($expected), false);


        $this->assertEquals($objectExpected, Builder\buildDifference($firstObject, $secondObject));
    }
}