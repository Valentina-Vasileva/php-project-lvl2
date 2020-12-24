<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use Differ\Differ;

class DifferTest extends TestCase
{
    private function getPathToFixture($fileName)
    {
        return __DIR__ . "/fixtures/" . $fileName;
    }

    public function testFileFormatException()
    {
        $firstPathToFile = $this->getPathToFixture('TestDoc1.json');
        $secondPathToFile = $this->getPathToFixture('TestDoc.doc');
        $this->expectExceptionMessage("The file format 'doc' is not supported");
        Differ\genDiff($firstPathToFile, $secondPathToFile, 'plain');
    }

    public function testReportFormatException()
    {
        $firstPathToFile = $this->getPathToFixture('TestDoc1.json');
        $secondPathToFile = $this->getPathToFixture('TestDoc2.json');
        $this->expectExceptionMessage("The report format '0' is not supported");
        Differ\genDiff($firstPathToFile, $secondPathToFile, '0');
    }

    public function testReadableFileException()
    {
        $firstPathToFile = $this->getPathToFixture('TestDoc1.json');
        $secondPathToFile = $this->getPathToFixture('TestNotReadable.json');
        $this->expectExceptionMessage("The file '{$secondPathToFile}' is not readable");
        Differ\genDiff($firstPathToFile, $secondPathToFile, 'json');
    }

    /**
     * @dataProvider additionProvider
     */

    public function testGenDiff($expected, $firstPathToFile, $secondPathToFile, $formatName = 'stylish')
    {
        $this->assertEquals($expected, Differ\genDiff($firstPathToFile, $secondPathToFile, $formatName));
    }

    public function additionProvider()
    {
        $stylishFormatName = 'stylish';
        $plainFormatName = 'plain';
        $jsonFormatName = 'json';

        $docAfterStylish = file_get_contents($this->getPathToFixture('ResultStylish.txt'));
        $docAfterPlain = file_get_contents($this->getPathToFixture('ResultPlain.txt'));
        $docAfterJson = file_get_contents($this->getPathToFixture('ResultJson.txt'));

        $docJsonFirst = $this->getPathToFixture('TestDoc1.json');
        $docJsonSecond = $this->getPathToFixture('TestDoc2.json');
        $docYamlFirst = $this->getPathToFixture('TestDoc1.yaml');
        $docYamlSecond = $this->getPathToFixture('TestDoc2.yml');

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
