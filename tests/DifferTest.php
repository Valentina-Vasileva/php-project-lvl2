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

    public function testFileExistenseException()
    {
        $firstPathToFile = $this->getPathToFixture('TestDoc1.json');
        $secondPathToFile = $this->getPathToFixture('DoesNotExist.json');
        $this->expectExceptionMessage("The file '{$secondPathToFile}' doesn't exist");
        Differ\genDiff($firstPathToFile, $secondPathToFile, 'json');
    }

    public function testExtensionException()
    {
        $firstPathToFile = $this->getPathToFixture('TestDoc1.json');
        $secondPathToFile = $this->getPathToFixture('TestDoc.doc');
        $this->expectExceptionMessage("The extension 'doc' is not supported");
        Differ\genDiff($firstPathToFile, $secondPathToFile, 'plain');
    }

    public function testFormatException()
    {
        $firstPathToFile = $this->getPathToFixture('TestDoc1.json');
        $secondPathToFile = $this->getPathToFixture('TestDoc2.json');
        $this->expectExceptionMessage("The report format 'smth' is not supported");
        Differ\genDiff($firstPathToFile, $secondPathToFile, 'smth');
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
        $docYamlSecond = $this->getPathToFixture('TestDoc2.yaml');

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
