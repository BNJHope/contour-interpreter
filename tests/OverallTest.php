<?php
namespace bnjhope\php_parser\tests;

use bnjhope\php_parser\FunctionParser;
use PHPUnit_Framework_TestCase;

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 19/07/2016
 * Time: 09:42
 */
class OverallTest extends PHPUnit_Framework_TestCase
{

    /**
     * The overall evaluator.
     * @var FunctionParser
     */
    public static $evaluator;

    public static function setUpBeforeClass()
    {
        self::$evaluator = new FunctionParser();
    }

    public function testNormalIf() {
        $stringToTest = "let a = 5
        if a = 4
        then return 4
        else if a = 5
        then return a";

        $result = 5;

        $this->assertEquals($result, self::$evaluator->resolve($stringToTest));
    }

    public function testNormal() {
        $structToTest = "let a = 5
        if a = 4
        then return 4
        else if a = 5
        then return a";

        $this->assertEquals(5,self::$evaluator->resolve($structToTest));
    }

    public function testShouldReturnString() {

        $stringToTest = "if 4 = 7
            then return \"should not return\"
            else return \"should return\"";

        $this->assertEquals("\"should return\"", self::$evaluator->resolve($stringToTest));
    }
}