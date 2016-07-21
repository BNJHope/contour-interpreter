<?php

namespace bnjhope\php_parser;

use bnjhope\php_parser\expressions\BooleanExpression;
use bnjhope\php_parser\expressions\IfStatement;
use bnjhope\php_parser\expressions\RawValueExpression;
use bnjhope\php_parser\expressions\ResultObject;
use bnjhope\php_parser\expressions\TagExpression;
use bnjhope\php_parser\expressions\ThenExpression;
use bnjhope\php_parser\expressions\VariableDeclarationExpression;
use bnjhope\php_parser\parsers\ExprParserController;
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

    public function testNormalFunction(){
        $program = "let a = #(test)
        if a < 7
        then return 9";

        $tagDec = TagExpression::withValues("test");
        $varDec = VariableDeclarationExpression::withValues(new RawValueExpression("a"), $tagDec);

        $boolexpr = BooleanExpression::withValues(new RawValueExpression("a"), new RawValueExpression("<"), new RawValueExpression("7"));
        $thenexpr = ThenExpression::withValue(new ResultObject("9"));
        $ifDec = IfStatement::withIfAndThen($boolexpr, $thenexpr);

        $func = array($varDec, $ifDec);

        $this->assertEquals($func, self::$exprParseController->parse($program));
    }

}