<?php

namespace contour\parser;

use contour\parser\expressions\BooleanExpression;
use contour\parser\expressions\IfStatement;
use contour\parser\expressions\OperationExpression;
use contour\parser\expressions\RawValueExpression;
use contour\parser\expressions\ResultObject;
use contour\parser\expressions\TagExpression;
use contour\parser\expressions\ThenExpression;
use contour\parser\expressions\VariableDeclarationExpression;
use contour\parser\parsers\ExprParserController;
use PHPUnit_Framework_TestCase;


/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 15/07/2016
 * Time: 16:11
 */
class ExprControllerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ExprParserController
     */
    private static $exprParseController;

    public static function setUpBeforeClass()
    {
        self::$exprParseController = new ExprParserController();
    }

    public function testNormalFunction()
    {
        $program = "let a = #(test)
        if a < 7
        then return 9";

        $tagDec = TagExpression::withValues("test");
        $varDec = VariableDeclarationExpression::withValues(new RawValueExpression("a"), $tagDec);

        $boolexpr = BooleanExpression::withValues(new RawValueExpression("a"), new OperationExpression("<"), new RawValueExpression("7"));
        $thenexpr = ThenExpression::withValue(new ResultObject(new RawValueExpression("9")));
        $ifDec = IfStatement::withIfAndThen($boolexpr, $thenexpr);

        $func = array($varDec, $ifDec);

        $this->assertEquals($func, self::$exprParseController->parse($program));
    }

}