<?php

namespace bnjhope\php_parser\tests;

use bnjhope\php_parser\expressions\BooleanExpression;
use bnjhope\php_parser\expressions\OperationExpression;
use bnjhope\php_parser\expressions\RawValueExpression;
use bnjhope\php_parser\expressions\ResultObject;
use bnjhope\php_parser\expressions\TagExpression;
use bnjhope\php_parser\expressions\ThenExpression;
use bnjhope\php_parser\expressions\VariableDeclarationExpression;
use bnjhope\php_parser\parsers\ExprParser;
use PHPUnit_Framework_TestCase;

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 13/07/2016
 * Time: 10:07
 */

class ParserTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ExprParser
     */
    private static $parser;

    /**
     * @var string
     * Test for a basic valid if statement.
     */
    private static $ifValidBasicInt = "if 7 = 8";


    /**
     * @var string
     * Test for a bracketed valid if statement.
     */
    private static $ifValidBracket1Int = "if (7 = 8)";

    /**
     * @var string
     * Test for another bracketed valid if statement
     */
    private static $ifValidBracket2Int = "if (7) = (8)";

    /**
     * @var string
     * Test for another bracketed valid if statement
     */
    private static $ifValidBracket3Int = "if (7 = 8) & (9 = 10)";

    /**
     * @var string
     * Tests if a nested bracketed if statement works.
     */
    private static $ifNestedBracketMixed = "if ((\"house\" = 8) & 1) > ((\"money\" < 2) | (\"trees\" = 8))";

    /**
     * @var string
     * Tests if a basic variable declaration works
     */
    private static $letValidStatement = "let test = 4";

    /**
     * @var string
     * Tests if a basic valid return statement works
     */
    private static $returnValidStatement = "return house";

    /**
     * @var string
     * Tests if a basic valid then-return statement works
     */
    private static $thenValidStatement = "then return house";

    /**
     * Constructs the parser to be used in the tests before they begin.
     */
    public static function setUpBeforeClass()
    {
        self::$parser = new ExprParser();
    }

    /**
     * Tests if "if 7 = 7" returns as expected
     */
    public function testIfValidBasicInt() {
        $objToTest = BooleanExpression::withValues(new RawValueExpression("7"), new OperationExpression("="),new RawValueExpression("8"));

        $this->assertEquals($objToTest, self::$parser->parse(self::$ifValidBasicInt));
    }

    /**
     * Tests if "if (7 = 8)" returns as expected
     */
    public function testIfValidBracket1Int() {
        $subobj = BooleanExpression::withValues(new RawValueExpression("7"), new OperationExpression("="), new RawValueExpression("8"));
        $objToTest = BooleanExpression::withValues($subobj);

        $this->assertEquals($objToTest, self::$parser->parse(self::$ifValidBracket1Int));
    }

    /**
     * Tests if "if (7) = (8)" returns as expected
     */
    public function testIfValidBracket2Int() {
        $subobj1 = BooleanExpression::withValues(new RawValueExpression("7"));
        $subobj2 = BooleanExpression::withValues(new RawValueExpression("8"));

        $objToTest = BooleanExpression::withValues($subobj1, new OperationExpression("="), $subobj2);

        $this->assertEquals($objToTest, self::$parser->parse(self::$ifValidBracket2Int));
    }

    /**
     * Tests if "if (7 = 8) & (9 = 10)" returns as expected
     */
    public function testIfValidBracket3Int() {
        $subobj1 = BooleanExpression::withValues(new RawValueExpression("7"), new OperationExpression("="), new RawValueExpression("8"));
        $subobj2 = BooleanExpression::withValues(new RawValueExpression("9"), new OperationExpression("="), new RawValueExpression("10"));

        $objToTest = BooleanExpression::withValues($subobj1, new OperationExpression("&"), $subobj2);

        $this->assertEquals($objToTest, self::$parser->parse(self::$ifValidBracket3Int));
    }

    /**
     * Tests if "if (("house" = 8) & 1) > (("money" < 2) | ("trees" = 8))" returns as expected
     */
    public function testMixedNested() {
        $subobj1 = BooleanExpression::withValues(new RawValueExpression("\"house\""), new OperationExpression("="), new RawValueExpression("8"));
        $subobj2 = BooleanExpression::withValues($subobj1,new OperationExpression("&"), new RawValueExpression("1"));

        $subobj3 = BooleanExpression::withValues(new RawValueExpression("\"money\""), new OperationExpression("<"), new RawValueExpression("2"));
        $subobj4 = BooleanExpression::withValues(new RawValueExpression("\"trees\""), new OperationExpression("="), new RawValueExpression("8"));
        $subobj5 = BooleanExpression::withValues($subobj3, new OperationExpression("|"), $subobj4);
        $objToTest = BooleanExpression::withValues($subobj2, new OperationExpression(">"), $subobj5);

        $this->assertEquals($objToTest, self::$parser->parse(self::$ifNestedBracketMixed));
    }

    /**
     * Tests if "let test = 4" returns as expected
     */
    public function testIfValidVariableStatement() {
        $objToTest = VariableDeclarationExpression::withValues(new RawValueExpression("test"), new RawValueExpression("4"));

        $this->assertEquals($objToTest, self::$parser->parse(self::$letValidStatement));
    }

    public function testIfValidReturnStatement() {
        $objToTest = ResultObject::withValue("house");

        $this->assertEquals($objToTest, self::$parser->parse(self::$returnValidStatement));

    }

    public function testIfValidThenReturnStatement() {
        $resObj = ResultObject::withValue("house");
        $objToTest = ThenExpression::withValue($resObj);

        $this->assertEquals($objToTest, self::$parser->parse(self::$thenValidStatement));
    }

    public function testTagExpression(){
        $objToTest = TagExpression::withValues("test");

        $objToTest = VariableDeclarationExpression::withValues(new RawValueExpression("a"), $objToTest);

        $this->assertEquals($objToTest, self::$parser->parse("let a = #(test)"));
    }


}