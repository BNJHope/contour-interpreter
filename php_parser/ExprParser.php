<?php
require 'ExpressionParseException.php';
require 'ParseStack.php';

class ExprParser {

    private $stack;

    public function __construct() {
        $this->stack = new ParseStack();
    }

	private $openBracket = '(';
	private $closeBracket = ')';

	private $linestarts = array("if", "then", "else", "let");
	private $boolCompare = array('=', '>', '<');
	private $booleanExprs = array('&', '|', '!');
	private $variableDeclarationMatch = "[A-Za-z0-9]*";

    private $expr;
    private $exprArray;
    private $parseIndex;

    /**
     * Parses a line of the function that is passed to the program.
     * @param $expr
     * @throws ExpressionParseException
     */
	function parse($expr) {

        $this->expr = $expr;
        $this->exprArray = str_split($this->expr);
        $this->parseIndex = 0;

        /**
         * @var object
         * The object that results from parsing the line.
         */
        $resultObject = null;

        /**
         * @var string
         * The first word in the line - determines the type of command that the line specifies.
         */
        try {
            $expressionType = $this->getType();
        } catch (ExpressionParseException $e) {
            throw new ExpressionParseException($e);
        }

        //depending on the first word of the expression, parse the part after that first word.
		switch($expressionType) {
			case $this->linestarts[0] :
				try {
                    $resultObject = $this->parseIf($statement = explode(" ", $expr, 2)[1]);
				} catch(ExpressionParseException $e) {
					throw new ExpressionParseException("Parser failed to parse IF statement - check syntax");
				}
				break;
			case $this->linestarts[1] :
				try {
                    $resultObject = $this->parseThen($statement = explode(" ", $expr, 2)[1]);
				} catch(ExpressionParseException $e) {
                    throw new ExpressionParseException("Parser failed to parse THEN statement - check syntax");
				}
				break;
			case $this->linestarts[2] :
				try {
                    $resultObject = $this->parseElse($statement = explode(" ", $expr, 2)[1]);
				} catch(ExpressionParseException $e) {
                    throw new ExpressionParseException("Parser failed to parse ELSE statement - check syntax");
				}
				break;
			case $this->linestarts[3] :
				try {
                    $resultObject = $this->parseVariable($statement = explode(" ", $expr, 2)[1]);
				} catch(ExpressionParseException $e) {
                    throw new ExpressionParseException("Parser failed to parse IF statement - check syntax");
				}
				break;
            default :
                throw new ExpressionParseException("Parser failed to recognise command keyword - check first word");
		}


	}

    function getType() {

        /**
         * The current char in the expression.
         */
        $currentChar = "";

        /**
         * Boolean to determine if a space
         */
        $spaceFound = false;

        /**
         * Result string to be returned
         */
        $result = "";

        /**
         * If there are no spaces in the string, then we need to terminate the while statement.
         */
        $limit = count($this->exprArray);

        //push everything upto the first space onto the stack
        while(!$spaceFound && $this->parseIndex < $limit) {
            $currentChar = $this->getNextChar();
            $this->stack->push();
            $spaceFound = $currentChar == " " ? true : false;
        }

        /**
         * If the loop terminated because the expression came to the end of the line then throw this exception.
         */
        if($this->parseIndex >= $limit) {
            throw new ExpressionParseException("No keyword detected in expression - no spaces in line for parser to stop on.");
        }

        //get the space character off the stack
        $this->stack->pop();

        //get the key by popping every character off of the stack
        //and forming a string from them.
        while(!$this->stack->isEmpty()) {
            $result = $this->stack->pop() . $result;
        }

        return $result;
    }

	function parseIf() {

        $result = new BooleanExpressionJoin();
        $closeBracketFound = false;

        while(!$closeBracketFound) {
            $currentChar = $this->getNextChar();
            
        }
	}

	function parseThen() {

	}

	function parseVariable() {

	}

	function parseElse() {

	}

	function parseBool() {

	}

    function getNextChar(){
        $charToReturn = $this->exprArray[$this->parseIndex];
        $this->parseIndex++;
        return $charToReturn;
    }

}
