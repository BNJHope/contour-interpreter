<?php

namespace bnjhope\php_parser;

use PHPUnit\Framework\TestCase;

require '../vendor/autoload.php';

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 15/07/2016
 * Time: 16:11
 */
class ExprControllerTests extends TestCase
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