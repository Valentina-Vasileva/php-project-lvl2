### Hexlet tests and linter status:
[![Actions Status](https://github.com/Valentina-Vasileva/php-project-lvl2/workflows/hexlet-check/badge.svg)](https://github.com/Valentina-Vasileva/php-project-lvl2/actions)
![](https://github.com/Valentina-Vasileva/php-project-lvl2/workflows/Tests%20and%20linter/badge.svg)
<a href="https://codeclimate.com/github/Valentina-Vasileva/php-project-lvl2/maintainability"><img src="https://api.codeclimate.com/v1/badges/eaa92505ee1615e22030/maintainability" /></a>
<a href="https://codeclimate.com/github/Valentina-Vasileva/php-project-lvl2/test_coverage"><img src="https://api.codeclimate.com/v1/badges/eaa92505ee1615e22030/test_coverage" /></a>

GENDIFF
========

A PHP package which shows difference (stylish, plain or json format) between two files (yaml, json).

Installation  
------------
### Via Composer

#### As a CLI:
    $ composer global require valentina-vasileva/php-project-lvl2
   
#### As a library to your project:
    $ composer require valentina-vasileva/php-project-lvl2
    
Usage
-----
#### As a CLI:  
    $ gendiff [--format <fmt>] <path_to_file1> <path_to_file2>
    
Also you can show yourself a descriptoin of the CLI:

    $ gendiff -h
    $ gendiff --help

#### As a library to your project:
    use function Differ\Differ\genDiff;
    
    genDiff($filepath1, $filepath2, $formatName = 'stylish');
    
### Examples of using the package

#### plain *.json files to stylish format:
[![asciicast](https://asciinema.org/a/Y1Rs8zuuV0BK0CwWpmdw2PMj4.svg)](https://asciinema.org/a/Y1Rs8zuuV0BK0CwWpmdw2PMj4)

#### plain *.yaml files to stylish format:
[![asciicast](https://asciinema.org/a/A6YxQ8x3tyhNzRFhh50k6p1Ir.svg)](https://asciinema.org/a/A6YxQ8x3tyhNzRFhh50k6p1Ir)

#### complex *.json/*.yaml files to stylish format:
[![asciicast](https://asciinema.org/a/uF8ACdXkqEyc5frWpo7mTPcnj.svg)](https://asciinema.org/a/uF8ACdXkqEyc5frWpo7mTPcnj)
[![asciicast](https://asciinema.org/a/oaYAGLM5GSjVTex6srZlSWyTV.svg)](https://asciinema.org/a/oaYAGLM5GSjVTex6srZlSWyTV)

#### complex *.json/*.yaml files to plain format:
[![asciicast](https://asciinema.org/a/55oPisizLekmOWzF798ILwKcV.svg)](https://asciinema.org/a/55oPisizLekmOWzF798ILwKcV)

#### complex *.json/*.yaml files to json format:
[![asciicast](https://asciinema.org/a/mEyJhajIGdS3NL1OzHiBGtRIh.svg)](https://asciinema.org/a/mEyJhajIGdS3NL1OzHiBGtRIh)
