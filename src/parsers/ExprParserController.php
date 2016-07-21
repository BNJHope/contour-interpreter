<?php

namespace contour\parser\parsers;

use contour\parser\expressions\BooleanExpression;
use contour\parser\exceptions\ExpressionParseException;
use contour\parser\expressions\ElseExpression;
use contour\parser\expressions\iExpression;
use contour\parser\expressions\IfStatement;
use contour\parser\expressions\ThenExpression;
use contour\parser\parsers\ExprParser;

include 'ExprParser.php';

/**
 * Class ExprParserController
 * @package bnjhope\php_parser\parsers
 */
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
     * @var array
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

        $this->parseTotal();

        return $this->function;
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

            //if there is at least one instruction that has been parsed and it is not an instance of an else expression
            //then there is a possibility that a previous if statement checked to see if it was an else expression, found it
            // was not and then terminated.
            //Therefore, check to see if there are any non else expressions left over and fetch it from the list of instructions.
            if($this->instructPtr > 0 && ($this->getCurrentElement() instanceof ElseExpression))
                $parsedLine = $this->getCurrentElement();
            else {

                //otherwise parse the next line in the function
                try {
                    $parsedLine = $this->getNextElement();
                } catch (ExpressionParseException $e) {
                    $this->throwParseError($e);
                }
            }

            //select what to do based on the type of the expression returned from the parser
            //if it is a boolean expression, then construct an if statement based off of it
            if($parsedLine instanceOf BooleanExpression)
                $exprToAdd = $this->compileIf($parsedLine);

            //if not, then just add it like normal to the array of function statements
            else
                $exprToAdd = $parsedLine;

            //push the expression that was parsed into the array of expressions to be evaluated after
            //all of the parsing is complete

            array_push($this->function, $exprToAdd);
        }

    }

    /**
     * Compiles a full if-then-else statement based on a boolean expression passed to the function and all
     * subsequent lines involved with it.
     * @param $parsedLine BooleanExpression
     * The boolean expression that the if statement is based upon.
     * @return IfStatement
     * The total if statement containing then and else clauses which can be evaluated by the evaluator.
     * @throws ExpressionParseException
     * If there are any syntactic parse errors then
     */
    private function compileIf($parsedLine) {
        /**
         * The if statement to return.
         */
        $totalIfStatement = new IfStatement();

        /**
         * @var ThenExpression | ElseExpression | iExpression
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

        //if the then subexpression is an instance of a boolean expression then convert the boolean expression
        //and any of the subsequent lines involved into a full if statement and replace the boolean expression on the
        //then statement with the full if statement attached to the then statement.
        if($thenSubExpr instanceof  BooleanExpression) {
            $nextLine->setSubExpression($this->compileIf($thenSubExpr));
        }

        //set the then clause of the if statement to be returned as the then statement which has been parsed
        $totalIfStatement->setThenConstructor($nextLine);

        if($this->isNext()){

            $exitCondition = false;

            try {
                //get the next line of the function
                $nextLine = $this->getNextElement();
            } catch (ExpressionParseException $e) {
                $this->throwParseError($e);
            }

            if(!($nextLine instanceof ElseExpression)) {
                    $exitCondition = true;
            }


            //while the next line is an else expression, parse it and attach it to the array of else expressions associated
            //with the if statement that is going to be returned at the end of this function
            while(!$exitCondition) {

                //if the subexpression of this else statement is a boolean expression then parse it and the subsequent lines
                //as an if statement and attach it as the subexpression of the else clause
                if($nextLine->getSubExpression() instanceof  BooleanExpression) {
                    $nextLine->setSubExpression($this->compileIf($nextLine->getSubExpression()));
                }

                //add the parsed else structure to the total if structure
                $totalIfStatement->addToElseConstructors($nextLine);

                if($this->isNext()) {
                    //set the next line as the next line to be parsed
                    $nextLine = $this->getNextElement();

                    $string = "";

                    $exitCondition = $nextLine instanceof ElseExpression ? false : true;
                } else {
                    $exitCondition = true;
                }

            }
        }

        return $totalIfStatement;
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
        return (!($this->instructPtr == count($this->linesToParse)));
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

        //trim any white space in the lines
        for($i = 0; $i < count($this->linesToParse); $i++) {
            $this->linesToParse[$i] = trim($this->linesToParse[$i]);
        }

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
