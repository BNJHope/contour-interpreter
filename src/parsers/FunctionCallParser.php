<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 22/07/2016
 * Time: 09:14
 */

namespace contour\parser\parsers;


class FunctionCallParser
{

    /**
     * @var ParseStack
     * The stack used for parsing.
     */
    private $stack;

    /**
     * @var string
     * The expression to parse in string form.
     */
    private $expr;

    /**
     * @var array
     * Representation of the expression to be parsed as an array of characters.
     */
    private $exprArray;

    /**
     * @var integer
     * The current location of the parser in the expression.
     */
    private $parseIndex;

    public function __construct()
    {
        $this->stack = new ParseStack();
    }

    public function parse() {

    }

    public function getNextChar(){

    }

    public function hasNext(){

    }

}