<?php
require 'ExpressionParseException.php';
require 'ParseStack.php';

class ExprParser {

    /**
     * @var ParseStack
     * The stack used for parsing.
     */
    private $stack;

    public function __construct() {
        $this->stack = new ParseStack();
    }

    /**
     * @var array
     * Words at the start of lines that determine what type of command is being called.
     */
	private $linestarts = array("if", "then", "else", "let");

    /**
     * @var array
     * Signs that represent boolean operations between expressions.
     */
	private $boolOps = array('=', '>', '<', '&', '|', '!');

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

    /**
     * Parses a line of the function that is passed to the program.
     * @param $expr
     * The expression to be parsed
     * @throws ExpressionParseException
     * If the expression is syntactically invalid, this exception is thrown.
     */
	function parse($expr) {

        //set the objects expression property to the expression passed to the
        $this->expr = $expr;

        //Set the objects expression array to the exploded expression string.
        $this->exprArray = str_split($this->expr);

        //Set the parse index of the object back to the start.
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

        $result = new BooleanExpression();
        while($this->isNext()) {
            $currentChar = $this->getNextChar();
            switch($currentChar) {

                //start the function on the new
                case "(" :
                    if($result->getFirstExpr() == null) $result->setFirstExpr($this->parseIf());
                    else $result->setSecondExpr($this->parseIf());
                    break;

                case " " :
                    $exprToAdd = "";
                    while($this->stack->top() != "(" && !$this->stack->isEmpty()) {
                        $exprToAdd = $this->stack->pop() . $exprToAdd;
                    }
                    if($result->getFirstExpr() == null) $result->setFirstExpr($exprToAdd);
                    elseif ($result->getOperator() == null) $result->setOperator($exprToAdd);
                    elseif ($result->getSecondExpr() == null) $result->setSecondExpr($exprToAdd);
                    else throw new ExpressionParseException("Illegal space character found after " . $exprToAdd);
                    break;

                case ")" :
                    $exprToAdd = "";
                    while($this->stack->top() != "(" && !$this->stack->isEmpty()) {
                        $exprToAdd = $this->stack->pop() . $exprToAdd;
                    }
                    if($this->stack->isEmpty())
                        throw new ExpressionParseException("Closed bracket found, no matching open bracket found before " . $exprToAdd . ".");

            }
        }
	}

	function parseThen() {

	}

	function parseElse() {

	}

    /**
     * Parses the line as a variable
     * @return VariableDeclarationExpression
     * The resulting key value pair of the variable declaration.
     * @throws ExpressionParseException
     * If the expression is syntactically incorrect.
     * @throws StackEmptyException
     * If the parser stack is empty when the parser tries to pop from it.
     */
    function parseVariable() {

        /**
         * Result to be returned from the function.
         */
        $result = new VariableDeclarationExpression();

        /**
         * The expression that will be added to the parse tree
         */
        $exprToAdd = "";

        //while there is still a character in the line
        while($this->isNext()) {

            //get the next character in the line
            $currentChar = $this->getNextChar();

            //if it is a space character then find fill in what part of the variable declaration it is
            if($currentChar == " ") {

                //set the expression to add back to empty again
                $exprToAdd = "";

                //empty the stack contents into expression to add
                while(!$this->stack->isEmpty()) {
                    $exprToAdd = $this->stack->pop() . $exprToAdd;
                }

                //if there is no variable identifier in the variable declaration expression
                //then the expression to add must be that identifier
                if($result->getIdentifier() == null)
                    $result->setIdentifier($exprToAdd);

                //if it is the equals symbol then ignore it
                elseif($exprToAdd == "=") {}

                //if it has been determined that the identifier is not empty
                //and that the expression to add is not the equals sign
                //then it must be the value of the variable
                elseif($result->getValue() == null) {
                    $result->setValue($exprToAdd);

                    //the set is complete - break from the parsing
                    break;
                }

                //if there are parsing issues, throw an exception
                else throw new ExpressionParseException("Error when declaring variable");
            }
        }

        //return the resulting structure
        return $result;
    }

    /**
     * @return mixed
     * Gets the next character in the expression.
     */
    function getNextChar(){
        $charToReturn = $this->exprArray[$this->parseIndex];
        $this->parseIndex++;
        return $charToReturn;
    }

    /**
     * @return bool
     * Checks if there is still more characters in the expression to be parsed.
     */
    function isNext() {
        return $this->parseIndex < (count($this->exprArray) - 1);
    }
}
