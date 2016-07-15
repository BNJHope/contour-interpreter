<?php

include 'ExprParser.php';

class ExprParserController
{

    /**
     * @var string
     * The function to parse, given as a string.
     */
    private $stringToParse;

    /**
     * @var array
     * The function to parse, represented as an array of lines separated by the new line character \n.
     */
    private $linesToParse;

    /**
     * @var ExprParser
     * The parser that the parse controller will use to parse each line of the function.
     */
    private $exprParser;

    /**
     * @var integer
     * The index of where the parse controller is in the instructions to be parsed.
     */
    private $instructPtr;

    /**
     * The objects which have been parsed by the parser.
     */
    private $function;

    public function __construct()
    {
        $this->linesToParse = array();
        $this->exprParser = new ExprParser();
    }

    public function parse($stringToParse)
    {

        //set up the parser by creating the array
        $this->initialiseParser($stringToParse);

        /**
         * @var iExpression[]
         * The function that the user is wanting to carry out, expressed in a list of expressions.
         */
        $function = [];

        /**
         * @var iExpression;
         *The line that has been parsed.
         */
        $parsedLine = null;

        $function = $this->parseTotal();
    }

    /**
     * Creates a list of iExpression instructions that need to be evaluated.
     */
    private function parseTotal() {

        /**
         * @var iExpression[]
         * The array of expression to be returned.
         */
        $result = array();

        /**
         *@var iExpression
         *The line that has just been returned from the parser.
         */
        $parsedLine = null;

        /**
         * @var iExpression
         * Expression to add to the function array.
         */
        $exprToAdd = null;

        //while there are still lines to be parsed.
        while($this->isNext()) {
            try {
                $parsedLine = $this->getNextElement();
            } catch (ExpressionParseException $e) {
                throw new ExpressionParseException($e);
            }

            //select what to do based on
            switch (true) {

                case $parsedLine instanceOf BooleanExpression:
                    $exprToAdd = $this->compileIf($parsedLine);
                    break;
                case $parsedLine instanceOf ThenExpression:

                    break;
            }
        }

        return $result;
    }

    /**
     * Pulls together an if statement if a boolean expression is found
     * @param $parsedLine
     */
    private function compileIf($parsedLine) {
        /**
         * The if statement to return.
         */
        $totalIfStatement = new IfStatement();

        /**
         * @var ThenExpression
         * The next line to be fetched
         */
        $nextLine = null;

        //set the boolean expression part of the if statement to the parsed line given as the parameter
        $totalIfStatement->setBoolExpression($parsedLine);

        //try to get the next line - it is syntactically incorrect if it is not a then statement
        try{
            $nextLine = $this->getNextElement();
        } catch (ExpressionParseException $e) {
            $this->throwParseError($e);
        }

        //if the line that followed the if statement was not a then statement then throw this error.
        if(!($nextLine instanceof ThenExpression))
            $this->throwParseError(null,"If statement not followed by a then statement.");

        /**
         * The subexpression of the then statement.
         * @var iExpression
         */
        $thenSubExpr = $nextLine->getSubExpression();

        switch(true) {

            case $thenSubExpr instanceOf BooleanExpression :
                $nextLine->setSubExpression($this->compileIf($thenSubExpr));
        }

    }

    /**
     * Gets the next expression object in the function statement.
     */
    private function getNextElement()
    {
        /**
         * @var iExpression
         */
        $parsedLine = null;
        try {
            //get the next line in the function as an expression object
            //which can be evaluated
            $parsedLine = $this->exprParser->parse($this->linesToParse[$this->instructPtr]);
        } catch (ExpressionParseException $e) {
            $this->throwParseError($e);
        }

        //increment the instruction pointer
        $this->instructPtr++;

        return $parsedLine;
    }

    /**
     * Gets the previous expression object in the function statement.
     */
    private function getCurrentElement()
    {
        return $this->function[$this->instructPtr - 1];
    }

    /**
     * Checks to see if there are still elements to be parsed.
     */
    private function isNext()
    {
        //returns true if the instruction pointer has not yet reached the last element of the array of function instructions.
        return ($this->instructPtr == count($this->linesToParse));
    }

    /**
     * Converts the given string into the parse controller's array of statements.
     * @param $stringToParse
     * The function to parse as a string.
     */
    private function initialiseParser($stringToParse)
    {
        //set the object's string to parse as the string given to it.
        $this->stringToParse = $stringToParse;

        //set the array of lines to parse as the string to parse
        //split at every occurrence of the new line character
        $this->linesToParse = explode("\n", $stringToParse);

        //set the instruction pointer back to the beginning
        $this->instructPtr = 0;

        //set the array of lines that have been parsed into a blank array
        $this->function = array();
    }

    /**
     * Error controller
     * @param $err ExpressionParseException
     * The error thrown by the parser.
     * @param string $extraErrorMsg
     * If the error was found in the
     * @throws ExpressionParseException
     */
    private function throwParseError($err = null, $extraErrorMsg = "") {

        $e = new ExpressionParseException("Parse Error line ". $this->instructPtr . " : " . ($extraErrorMsg == "" ? $err->getMessage() : $extraErrorMsg));

        throw $e;
    }
}
