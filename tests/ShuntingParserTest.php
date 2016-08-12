<?php
/**
 * Created by PhpStorm.
 * User: bnjhope
 * Date: 8/9/16
 * Time: 1:27 PM
 */

namespace contour\parser\tests;


use contour\parser\expressions\OperationExpression;
use contour\parser\expressions\RawValueExpression;
use contour\parser\expressions\TagExpression;
use contour\parser\parsers\ExprParser;
use PHPUnit_Framework_TestCase;

class ShuntingParserTest extends PHPUnit_Framework_TestCase
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

    public function testBasicArithmetic()
    {
        $test = "3+3*2";
        $result = [new RawValueExpression(3), new RawValueExpression(3), new RawValueExpression(2), new OperationExpression("*"), new OperationExpression("+")];
        $this->assertEquals($result, self::$parser->testShunt($test));
    }

    public function testWithWhitespace()
    {
        $test = "3 + 3 * 2";
        $result = [new RawValueExpression(3), new RawValueExpression(3), new RawValueExpression(2), new OperationExpression("*"), new OperationExpression("+")];
        $this->assertEquals($result, self::$parser->testShunt($test));
    }

    public function testBrackets()
    {
        $test = "(3 + 3) * 2";
        $result = [new RawValueExpression(3), new RawValueExpression(3), new OperationExpression("+"), new RawValueExpression(2), new OperationExpression("*")];
        $this->assertEquals($result, self::$parser->testShunt($test));
    }

    public function testRawString()
    {
        $test = "\"test\"";
        $result = [new RawValueExpression("\"test\"")];
        $this->assertEquals($result, self::$parser->testShunt($test));
    }

    public function testBracketedChars()
    {
        $test = "(3) + (3)";
        $result = [new RawValueExpression(3), new RawValueExpression(3), new OperationExpression("+")];
        $this->assertEquals($result, self::$parser->testShunt($test));
    }

    public function testExtended()
    {
        $test = "3 * (4&5)+ \"klsefs\" / (test |2)";
        $result = [new RawValueExpression(3), new RawValueExpression(4), new RawValueExpression(5), new OperationExpression("&"), new OperationExpression("*"), new RawValueExpression("\"klsefs\""), new RawValueExpression("test"), new RawValueExpression(2), new OperationExpression("|"), new OperationExpression("/"), new OperationExpression("+")];
        $this->assertEquals($result, self::$parser->testShunt($test));
    }

    public function testTag()
    {
        $test = "#(test1, test2) + 3";
        $result = [TagExpression::withValues(["test1", "test2"]), new RawValueExpression(3), new OperationExpression("+")];
        $this->assertEquals($result, self::$parser->testShunt($test));
    }
}