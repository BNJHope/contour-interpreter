<?php

namespace bnjhope\php_parser;

use PHPUnit\Framework\TestCase;

require '../vendor/autoload.php';

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
    private $evaluator;

    public static function setUpBeforeClass()
    {
        self::$evaluator = new FunctionParser();
    }

    public function testNormalIf() {
        $stringToTest = "if 5 = 6
        then return \"no\"
        else return \"yes\"";

        $result = "\"yes\"";

        $this->assertEquals($result, $this->evaluator->resolve($stringToTest));
    }

}