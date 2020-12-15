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

    /**
     * @dataProvider additionProvider
     */

    public function testGetDifference($expected, $firstFile, $secondFile, $format = 'stylish')
    {
        $this->assertEquals($expected, Differ\getDifference($firstFile, $secondFile, $format));
    }

    public function additionProvider()
    {
        $stylishFormat = 'stylish';
        $plainFormat = 'plain';

        $docAfterStylish = file_get_contents(__DIR__ . '/fixtures/ResultStylish.txt');
        $docAfterPlain = file_get_contents(__DIR__ . '/fixtures/ResultPlain.txt');
        
        $docJsonFirst = __DIR__ . '/fixtures/TestDoc1.json';
        $docJsonSecond = __DIR__ . '/fixtures/TestDoc2.json';
        $docYamlFirst = __DIR__ . '/fixtures/TestDoc1.yaml';
        $docYamlSecond = __DIR__ . '/fixtures/TestDoc2.yaml';

        return [
            [$docAfterStylish, $docJsonFirst, $docJsonSecond],
            [$docAfterStylish, $docYamlFirst, $docYamlSecond, $stylishFormat],
            [$docAfterPlain, $docJsonFirst, $docJsonSecond, $plainFormat],
            [$docAfterPlain, $docYamlFirst, $docYamlSecond, $plainFormat]
        ];
    }
}
