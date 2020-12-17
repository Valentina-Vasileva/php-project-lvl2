<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use Differ\Differ;

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

    public function testPrintDifference()
    {
        $firstFile = __DIR__ . '/fixtures/TestDoc1.json';
        $secondFile = __DIR__ . '/fixtures/TestDoc2.json';

        $this->assertEquals(null, Differ\printDifference($firstFile, $secondFile, 'stylish'));
    }

    /**
     * @dataProvider additionProvider
     */

    public function testGenDiff($expected, $firstFile, $secondFile, $formatName = 'stylish')
    {
        $this->assertEquals($expected, Differ\genDiff($firstFile, $secondFile, $formatName));
    }

    public function additionProvider()
    {
        $stylishFormatName = 'stylish';
        $plainFormatName = 'plain';
        $jsonFormatName = 'json';


        $docAfterStylish = file_get_contents(__DIR__ . '/fixtures/ResultStylish.txt');
        $docAfterPlain = file_get_contents(__DIR__ . '/fixtures/ResultPlain.txt');
        $docAfterJson = file_get_contents(__DIR__ . '/fixtures/ResultJson.txt');

        $docJsonFirst = __DIR__ . '/fixtures/TestDoc1.json';
        $docJsonSecond = __DIR__ . '/fixtures/TestDoc2.json';
        $docYamlFirst = __DIR__ . '/fixtures/TestDoc1.yaml';
        $docYamlSecond = __DIR__ . '/fixtures/TestDoc2.yaml';

        return [
            [$docAfterStylish, $docJsonFirst, $docJsonSecond],
            [$docAfterStylish, $docYamlFirst, $docYamlSecond, $stylishFormatName],
            [$docAfterPlain, $docJsonFirst, $docJsonSecond, $plainFormatName],
            [$docAfterPlain, $docYamlFirst, $docYamlSecond, $plainFormatName],
            [$docAfterJson, $docJsonFirst, $docJsonSecond, $jsonFormatName],
            [$docAfterJson, $docYamlFirst, $docYamlSecond, $jsonFormatName]
        ];
    }
}
