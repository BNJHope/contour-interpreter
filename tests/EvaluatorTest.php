<?php

namespace contour\parser\tests;


use contour\parser\evaluators\ExprEvaluator;
use contour\parser\parsers\ExprParserController;
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
        $structToTest = self::$parser->parse("params ()
            if 4 = 7
            then return \"should not return\"
            else return \"should return\"");

        $this->assertEquals("\"should return\"" ,self::$evaluator->evaluate($structToTest, []));
    }

    public function testNormal() {
        $structToTest = self::$parser->parse("params (x, y)
        let a = x * (2 + 2)
        if a=y
        then return 1
        else if a + x = 3
        then return 0");

        $result = self::$evaluator->evaluate($structToTest, [2,8]);

        $this->assertEquals(1,$result);
    }
}