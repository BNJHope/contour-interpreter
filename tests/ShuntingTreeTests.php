<?php
/**
 * Created by PhpStorm.
 * User: bnjhope
 * Date: 8/11/16
 * Time: 10:59 AM
 */

namespace contour\parser\tests;

use contour\parser\expressions\BooleanExpression;
use contour\parser\expressions\OperationExpression;
use contour\parser\expressions\RawValueExpression;
use contour\parser\expressions\TagExpression;
use contour\parser\parsers\ExprParser;
use PHPUnit_Framework_TestCase;

class ShuntingTreeTests extends PHPUnit_Framework_TestCase
{

    /**
     * The parser to be used in the tests.
     * @var ExprParser
     */
    private static $parser;

    public static function setUpBeforeClass()
    {
        self::$parser = new ExprParser();
    }

    public function testBasicArithmetic() {
        $test = "3+3*2";
        $multiplyExpression = BooleanExpression::withValues(new RawValueExpression(3), new OperationExpression("*"), new RawValueExpression(2));
        $overallExpression = BooleanExpression::withValues(new RawValueExpression(3), new OperationExpression("+"), $multiplyExpression);
        $this->assertEquals($overallExpression, self::$parser->testShuntTree($test));
    }

    public function testBrackets() {
        $test = "(3 + 3) * 2";
        $bracketsExpression = BooleanExpression::withValues(new RawValueExpression(3), new OperationExpression("+"), new RawValueExpression(3));
        $result = BooleanExpression::withValues($bracketsExpression, new OperationExpression("*"), new RawValueExpression(2));
        $this->assertEquals($result, self::$parser->testShuntTree($test));
    }

    public function testRawString() {
        $test = "\"test\"";
        $result = BooleanExpression::withValues(new RawValueExpression("\"test\""));
        $this->assertEquals($result, self::$parser->testShuntTree($test));
    }

    public function testBracketedChars() {
        $test = "(3) + (3)";
        $result = BooleanExpression::withValues(new RawValueExpression(3), new OperationExpression("+"), new RawValueExpression(3));
        $this->assertEquals($result, self::$parser->testShuntTree($test));
    }

    public function testExtended() {
        $test = "3 * (4&5)+ \"klsefs\" / (test |2)";

        //Left Hand side
        $andExpression = BooleanExpression::withValues(new RawValueExpression(4), new OperationExpression("&"),  new RawValueExpression(5));
        $multiplicationExpr = BooleanExpression::withValues(new RawValueExpression(3), new OperationExpression("*"), $andExpression);

        //Right hand side
        $orExpr = BooleanExpression::withValues(new RawValueExpression("test"), new OperationExpression("|"), new RawValueExpression(2));
        $divExpr = BooleanExpression::withValues(new RawValueExpression("\"klsefs\""), new OperationExpression("/"), $orExpr);

        //total
        $result = BooleanExpression::withValues($multiplicationExpr, new OperationExpression("+"), $divExpr);
        $this->assertEquals($result, self::$parser->testShuntTree($test));
    }

    public function testTag() {
        $test = "#(test1, test2) + 3";
        $result = BooleanExpression::withValues(TagExpression::withValues(["test1", "test2"]), new OperationExpression("+"), new RawValueExpression(3));
        $this->assertEquals($result, self::$parser->testShuntTree($test));
    }
}