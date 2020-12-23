<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use Differ\Differ;

class DifferTest extends TestCase
{
    public function testFileExistenseException()
    {
        $firstFile = __DIR__ . '/fixtures/TestDoc1.json';
        $secondFile = __DIR__ . '/fixtures/DoesNotExist.json';
        $this->expectOutputString("The file '{$secondFile}' doesn't exist\n");
        Differ\genDiff($firstFile, $secondFile, 'json');
    }

    public function testExtensionException()
    {
        $firstFile = __DIR__ . '/fixtures/TestDoc1.json';
        $secondFile = __DIR__ . '/fixtures/TestDoc.doc';
        $this->expectOutputString("The extension 'doc' is not supported\n");
        Differ\genDiff($firstFile, $secondFile, 'plain');
    }

    public function testFormatException()
    {
        $firstFile = __DIR__ . '/fixtures/TestDoc1.json';
        $secondFile = __DIR__ . '/fixtures/TestDoc2.json';
        $this->expectOutputString("The report format 'smth' is not supported\n");
        Differ\genDiff($firstFile, $secondFile, 'smth');
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
