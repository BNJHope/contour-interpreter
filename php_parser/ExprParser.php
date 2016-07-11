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
		$expressionType = $this->getType();

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

        while($spaceFound = false && $this->parseIndex < $limit) {
            $currentChar = $this->getNextChar();
            $this->stack->push();
            $spaceFound = $currentChar == " " ? true : false;
        }

        //get the space character off the stack
        $this->stack->pop();

        while(!$this->stack->isEmpty()) {
            $contents = $this->stack
        }


    }

	function parseIf() {
        $statement_array = str_split($statement);

        $boolResult = new BooleanExpression();

        for($i = 0; $i < count($statement_array); $i++) {
            $charToAdd = $statement_array[$i];
            if($charToAdd == "(") {
                $this->stack->push($charToAdd);
                $this->parseBool(array_shift($statement_array));
            }
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

class ParentExpression {

}

class BooleanExpression {
	private $firstExpr = "";
	private $secondExpr = "";
	private $operator = "";
}


class VariableDeclaration {
	private $identifier;
	private $value;
}
