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
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];
        $docAfter = file_get_contents(__DIR__ . '/fixtures/TestDoc3.txt');
        $this->assertEquals($docAfter, Differ\formatResult($docBefore));
    }

    public function testGetDifference()
    {
        $firstFile = __DIR__ . '/fixtures/TestDoc1.json';
        $secondFile = __DIR__ . '/fixtures/TestDoc2.json';
        $docAfter = file_get_contents(__DIR__ . '/fixtures/TestDoc4.txt');
        $this->assertEquals($docAfter, Differ\getDifference($firstFile, $secondFile));
    }
}
