<?php

namespace bnjhope\php_parser\tests;


use bnjhope\php_parser\evaluators\ExprEvaluator;
use bnjhope\php_parser\parsers\ExprParserController;
use PHPUnit_Framework_TestCase;

class EvaluatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ExprEvaluator
     */
    public static $evaluator;

    /**
     * @var ExprParserController
     */
    public static $parser;

    public static function setUpBeforeClass()
    {
        self::$evaluator = new ExprEvaluator();
        self::$parser = new ExprParserController();
    }

    public function testIf() {
        $structToTest = self::$parser->parse("if 4 = 7
            then return \"should not return\"
            else return \"should return\"");

        $this->assertEquals("\"should return\"" ,self::$evaluator->evaluate($structToTest));
    }

    public function testNormal() {
        $structToTest = self::$parser->parse("let a = 5
        if a = 4
        then return 4
        else if a = 5
        then return a");

        $this->assertEquals(5,self::$evaluator->evaluate($structToTest));
    }
}