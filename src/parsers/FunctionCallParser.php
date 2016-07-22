<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 22/07/2016
 * Time: 09:14
 */

namespace contour\parser\parsers;


use contour\parser\exceptions\ExpressionParseException;

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

    /**
     * The keyword used for declaring a function.
     * @var string
     */
    public static $funcConst = "func";

    public function parse($expr) {

        //set the parse index to the beginning of the expression
        $this->parseIndex = 0;

        //splits the expression into 2 parts - first part is everything before and not including the hashtag, the second part
        //is everything after and not including the hashtag.
        $contents = explode("/", $expr, 2);

        if($contents[0] != self::$funcConst) {
            throw new ExpressionParseException("func keyword not found before hashtag");
        }

        //sets the expression to be parsed as the two bracket statements which contain the location of the function
        //to be evaluated and the parameters to pass to the function
        $this->expr = $contents[1];

        //split the expression into an array of characters
        $this->exprArray = str_split($this->expr);


    }

    function parseCommaSeparatedBrackets() {

        /**
         * @var string
         */
        $currentChar = "";

        /**
         * @var bool
         */
        $closeBracketFound = false;

        /**
         *
         */
        $strToAdd = "";

        /**
         * @var string[]
         */
        $result = array();

        if($this->getNextChar() != "(") {
            throw new ExpressionParseException("Open bracket not found");
        }

        while(!$closeBracketFound && $this->hasNext()) {

            $currentChar = $this->getNextChar();

            switch($currentChar) {
                case "," :
                    array_push($result, trim($strToAdd));
                    $strToAdd = "";
                    break;
                case ")" :
                    array_push($result, trim($strToAdd));
                    $closeBracketFound = true;
                    break;
                default :
                    $strToAdd .= $currentChar;
            }
        }

        return $result;
    }

    /**
     * @return string
     * Gets the next character in the expression.
     */
    function getNextChar()
    {
        $charToReturn = $this->exprArray[$this->parseIndex];
        $this->parseIndex++;
        return $charToReturn;
    }

    /**
     * @return bool
     * Checks if there is still more characters in the expression to be parsed.
     */
    function hasNext()
    {
        return $this->parseIndex < (count($this->exprArray));
    }
}