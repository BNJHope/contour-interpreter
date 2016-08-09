<?php
/**
 * Created by PhpStorm.
 * User: bnjhope
 * Date: 8/9/16
 * Time: 1:27 PM
 */

namespace contour\parser\tests;


use contour\parser\parsers\ExprParser;
use PHPUnit_Framework_TestCase;

class ShuntingParser extends PHPUnit_Framework_TestCase
{

    private static $parser;

    public static function setUpBeforeClass()
    {
        self::$parser = new ExprParser();
    }

    public function testBasicArithmetic() {

    }

}