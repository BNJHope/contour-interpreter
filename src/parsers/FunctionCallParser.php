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
     * The keyword used for declaring a function.
     * @var string
     */
    public static $funcConst = "func";
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

    public function parse($expr)
    {

        //set the parse index to the beginning of the expression
        $this->parseIndex = 0;

        //splits the expression into 2 parts - first part is everything before and not including the hashtag, the second part
        //is everything after and not including the hashtag.
        $contents = explode("/", $expr, 2);

        if ($contents[0] != self::$funcConst) {
            throw new ExpressionParseException("func keyword not found before hashtag");
        }

        //sets the expression to be parsed as the two bracket statements which contain the location of the function
        //to be evaluated and the parameters to pass to the function
        $this->expr = $contents[1];

        //split the expression into an array of characters
        $this->exprArray = str_split($this->expr);

        /**
         * The tags that represent the location of the function to be evaluated in the sheets.
         * @var string[]
         */
        $funcLocation = $this->parseCommaSeparatedBrackets();

        /**
         * The parameters that will be passed
         */
        $params = $this->parseCommaSeparatedBrackets();

    }

    /**
     * Returns the contents of string that has comma separated values within brackets.
     * @return array
     * The strings separated by the commas within the brackets.
     * @throws ExpressionParseException
     * If the expression is syntactically incorrect, then this expression is thrown.
     */
    function parseCommaSeparatedBrackets()
    {

        /**
         * The current character being looked at by the parser.
         * @var string
         */
        $currentChar = "";

        /**
         * Determines if the parser has found a close bracket or not yet while parsing the expression.
         * If it has found one, this variable turns true and the parser loop ends.
         * @var bool
         */
        $closeBracketFound = false;

        /**
         * Holds a string of characters of a result to be added to the results array.
         * @var string
         */
        $strToAdd = "";

        /**
         * The array of results of strings to be returned to the calling function.
         * @var string[]
         */
        $result = array();

        //it is a syntax error if no open bracket is found at the start. If there is none found, then
        //throw this exception.
        if ($this->getNextChar() != "(") {
            throw new ExpressionParseException("Open bracket not found");
        }

        //while the close bracket has not been found and while the
        //there are still characters to be parsed in the stream
        while (!$closeBracketFound && $this->hasNext()) {

            //set the current character as the next character
            //in the stream
            $currentChar = $this->getNextChar();

            //determine what to do based on what the current character is
            switch ($currentChar) {

                //if its a comma, add the new value to the array
                case "," :
                    array_push($result, trim($strToAdd));
                    $strToAdd = "";
                    break;

                //if its a close bracket, add the last value and exit the loop
                case ")" :
                    array_push($result, trim($strToAdd));
                    $closeBracketFound = true;
                    break;

                //otherwise, just add the character to the current string being read
                default :
                    $strToAdd .= $currentChar;
            }
        }

        //return the array of results
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