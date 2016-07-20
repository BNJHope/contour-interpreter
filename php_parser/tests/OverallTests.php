<?php
namespace bnjhope\php_parser\tests;

require '../../vendor/autoload.php';

use bnjhope\php_parser\FunctionParser;
use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 19/07/2016
 * Time: 09:42
 */
class OverallTests extends TestCase
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
}