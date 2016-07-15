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

            switch (true) {

                case $parsedLine instanceOf BooleanExpression:

                    break;
                case $parsedLine instanceOf ThenExpression:

            }
        }

        return $result;
    }

    /**
     * Gets the next expression object in the function statement.
     */
    private function getNextElement()
    {
        try {
            //get the next line in the function as an expression object
            //which can be evaluated
            $parsedLine = $this->exprParser->parse($this->linesToParse[$this->instructPtr]);
        } catch (ExpressionParseException $e) {
            throw new Exception("Parsing issue on line " . strval($this->instructPtr + 1) . " : " . $e->getMessage());
        }

        $this->instructPtr++;

        return $parsedLine;
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
        $this->stringToParse = $stringToParse;
        $this->linesToParse = explode("\n", $stringToParse);
        $this->instructPtr = 0;
    }

}
