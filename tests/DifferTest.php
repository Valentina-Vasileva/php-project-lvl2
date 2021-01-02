<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private function getFixturePath($fileName)
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, "fixtures", $fileName]);
    }

    public function testFileFormatException()
    {
        $firstPathToFile = $this->getFixturePath('TestDoc1.json');
        $secondPathToFile = $this->getFixturePath('TestDoc.doc');
        $this->expectExceptionMessage("The file format 'doc' is not supported");
        genDiff($firstPathToFile, $secondPathToFile, 'plain');
    }

    public function testReportFormatException()
    {
        $firstPathToFile = $this->getFixturePath('TestDoc1.json');
        $secondPathToFile = $this->getFixturePath('TestDoc2.json');
        $this->expectExceptionMessage("The report format '0' is not supported");
        genDiff($firstPathToFile, $secondPathToFile, '0');
    }

    public function testReadableFileException()
    {
        $firstPathToFile = $this->getFixturePath('TestDoc1.json');
        $secondPathToFile = $this->getFixturePath('TestNotReadable.json');
        $this->expectExceptionMessage("The file '{$secondPathToFile}' is not readable");
        genDiff($firstPathToFile, $secondPathToFile, 'json');
    }

    /**
     * @dataProvider additionProvider
     */

    public function testGenDiff($expected, $firstPathToFile, $secondPathToFile, $formatName = 'stylish')
    {
        $this->assertEquals($expected, genDiff($firstPathToFile, $secondPathToFile, $formatName));
    }

    public function additionProvider()
    {
        $stylishFormatName = 'stylish';
        $plainFormatName = 'plain';
        $jsonFormatName = 'json';

        $docAfterStylish = file_get_contents($this->getFixturePath('ResultStylish.txt'));
        $docAfterPlain = file_get_contents($this->getFixturePath('ResultPlain.txt'));
        $docAfterJson = file_get_contents($this->getFixturePath('ResultJson.txt'));

        $docJsonFirst = $this->getFixturePath('TestDoc1.json');
        $docJsonSecond = $this->getFixturePath('TestDoc2.json');
        $docYamlFirst = $this->getFixturePath('TestDoc1.yaml');
        $docYamlSecond = $this->getFixturePath('TestDoc2.yml');

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
