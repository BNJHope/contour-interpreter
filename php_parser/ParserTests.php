<?php

use PHPUnit\Framework\TestCase;
include 'ExprParser.php';
include 'BooleanExpression.php';
include 'RawValueExpression.php';
include 'OperationExpression.php';
include 'VariableDeclarationExpression.php';

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 13/07/2016
 * Time: 10:07
 */

class ParserTests extends TestCase
{

    /**
     * @var ExprParser
     */
    private static $parser;

    /**
     * @var string
     * Test for a basic valid if statement.
     */
    private static $ifValidBasicInt = "if 7 = 7";


    /**
     * @var string
     * Test for a bracketed valid if statement.
     */
    private static $ifValidBracket1Int = "if (7 = 7)";

    /**
     * @var string
     * Test for another bracketed valid if statement
     */
    private static $ifValidBracket2Int = "if (7) = (7)";

    /**
     *
     */
    private static $letValidStatement = "let test = 4";

    public static function setUpBeforeClass()
    {
        self::$parser = new ExprParser();
    }

    public function testIfValidBasicInt() {
        $objToTest = BooleanExpression::withValues(new RawValueExpression("7"), new OperationExpression("="),new RawValueExpression("7"));

        $this->assertEquals($objToTest, self::$parser->parse(self::$ifValidBasicInt));
    }

    public function testIfValidVariableStatement() {
        $objToTest = VariableDeclarationExpression::withValues(new RawValueExpression("test"), new RawValueExpression("4"));

        $this->assertEquals($objToTest, self::$parser->parse(self::$letValidStatement));
    }

}