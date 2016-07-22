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

    public function parse($expr) {

        //set this objects expression to the expression passed to the parse function so that all properties and methods
        //of the object can access the expression.
        $this->expr = $expr;

        //split the expression into an array of characters
        $this->exprArray = str_split($this->expr);

        //set the parse index to the beginning of the expression
        $this->parseIndex = 0;

        //test to see that the keyword is "func", if it is not then it is not a function being parsed.
        $keyword = $this->getUptoHashtag();
    }

    public function getUptoHashtag() {

    }

    public function getNextChar(){

    }

    public function hasNext(){

    }

}