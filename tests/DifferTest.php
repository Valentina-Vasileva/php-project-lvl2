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

    /**
     * @dataProvider additionProvider
     */

    public function testGetDifference($expected, $firstFile, $secondFile)
    {
        $this->assertEquals($expected, Differ\getDifference($firstFile, $secondFile));
    }

    public function additionProvider()
    {
        $docAfter = file_get_contents(__DIR__ . '/fixtures/TestDoc4.txt');
        return [
            [$docAfter,  __DIR__ . '/fixtures/TestDoc1.json', __DIR__ . '/fixtures/TestDoc2.json'],
            [$docAfter,  __DIR__ . '/fixtures/TestDoc1.yaml', __DIR__ . '/fixtures/TestDoc2.yaml']
        ];
    }

}
