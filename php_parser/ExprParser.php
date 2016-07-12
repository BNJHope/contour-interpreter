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
	private $linestarts = array("if", "then", "else", "let", "return");

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
     * @return iExpression
     * Expression object which can be evaluated
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
                    $resultObject = $this->parseIf();
				} catch(ExpressionParseException $e) {
					throw new ExpressionParseException($e);
				}
				break;
			case $this->linestarts[1] :
				try {
                    $resultObject = $this->parseThen();
				} catch(ExpressionParseException $e) {
                    throw new ExpressionParseException($e);
				}
				break;
			case $this->linestarts[2] :
				try {
                    $resultObject = $this->parseElse();
				} catch(ExpressionParseException $e) {
                    throw new ExpressionParseException($e);
				}
				break;
			case $this->linestarts[3] :
				try {
                    $resultObject = $this->parseVariable();
				} catch(ExpressionParseException $e) {
                    throw new ExpressionParseException($e);
				}
				break;
            default :
                throw new ExpressionParseException("Parser failed to recognise command keyword - check first word");
		}

        return $resultObject;
	}

    /**
     * Gets the type of the current line to be parsed.
     * @return string
     * The type of the line that is being parsed.
     * @throws ExpressionParseException
     * If the line is syntactically incorrect, this exception is thrown.
     * @throws StackEmptyException
     * If there is an attempt to pop from the top of stack when it is empty then this is thrown.
     */
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
            $this->stack->push($currentChar);
            $spaceFound = $currentChar == " " ? true : false;
        }

        //if the loop terminated because the expression came to the end of the line then throw this exception.
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

    /**
     * Parses an if statement in the grammar.
     * @throws ExpressionParseException
     * If the statement is syntactically incorrect, then this exception is thrown.
     * @throws StackEmptyException
     * If the parser tries to pop off the top of the stack when it is empty then this exception is thrown.
     */
	function parseIf() {

        /*
         * The result to be returned from parsing the expression.
         */
        $result = new BooleanExpression();

        /*
         * Determines if the result expression is full or not.
         */
        $expressionComplete = false;

        //while there are still characters to be parsed
        //or the expression is not yet complete
        while($this->isNext() && !$expressionComplete) {

            //get the next char in the stream
            $currentChar = $this->getNextChar();

            //decide what to do next depending on what that character is
            switch($currentChar) {

                //parse the sub expression recursively if its an open bracket
                case "(" :
                    if($result->getFirstExpr() == null) {

                        //try to set the first expression of the result to this subexpression
                        try {
                            $result->setFirstExpr($this->parseIf());
                        } catch (ExpressionParseException $e) {
                            throw new ExpressionParseException ($e);
                        } catch (StackEmptyException $e) {
                            throw new StackEmptyException($e);
                        }
                    }

                    elseif ($result->getSecondExpr() == null) {

                        //try to set the second expression of the result to this subexpression
                        try {
                            $result->setSecondExpr($this->parseIf());
                        } catch (ExpressionParseException $e) {
                            throw new ExpressionParseException ($e);
                        } catch (StackEmptyException $e) {
                            throw new StackEmptyException($e);
                        }

                        //expression now full - exit the parsing
                        $expressionComplete = true;
                    }

                    else
                        //throw exception if there are problems
                        throw new ExpressionParseException("Error on open bracket");
                    break;

                case " " :
                    //set the expression to add back to empty so it can be filled
                    $exprToAdd = "";

                    //while the top of the stack is not an open bracket and while the stack is not empty
                    while($this->stack->top() != "(" && !$this->stack->isEmpty()) {

                        //pop off the top of the stack into the expression to add string
                        $exprToAdd = $this->stack->pop() . $exprToAdd;
                    }

                    //if the first expression is empty then use this result to fill it
                    if($result->getFirstExpr() == null)
                        $result->setFirstExpr(new RawValueExpression($exprToAdd));

                    //if the first expression is not empty but the operator is then fill it
                    elseif ($result->getOperator() == null)
                        $result->setOperator($exprToAdd);

                    //if the first expression and operator are not empty but the second expression is then fill it
                    elseif ($result->getSecondExpr() == null) {
                        $result->setSecondExpr(new RawValueExpression($exprToAdd));
                        $expressionComplete = true;
                    }
                    else
                        throw new ExpressionParseException("Illegal space character found after " . $exprToAdd);
                    break;

                case ")" :
                    //set the expression to add back to empty so it can be filled
                    $exprToAdd = "";

                    //while the top of the stack is not an open bracket and while the stack is not empty
                    while($this->stack->top() != "(" && !$this->stack->isEmpty()) {
                        $exprToAdd = $this->stack->pop() . $exprToAdd;
                    }

                    if($this->stack->isEmpty())
                        throw new ExpressionParseException("Closed bracket found, no matching open bracket found before " . $exprToAdd . ".");
                    if($result->getFirstExpr() != null && $result->getOperator() != null && $result->getSecondExpr() == null){
                        $result->setSecondExpr(new RawValueExpression($exprToAdd));
                        $expressionComplete = true;
                    } else
                        throw new ExpressionParseException("Illegal close bracket - closes before expression is finished.");
                    break;

                //if its not a special character, just add it to the stack
                default :
                    $this->stack->push($currentChar);
                    break;
            }
        }

        //if the parser reaches the end of the line and the operator and first expression are full and the second one is empty
        //then fill the second expression with the contents of the stack up to the first opening bracket or until the stack is empty
        if(!$this->isNext() && $result->getFirstExpr() != null && $result->getOperator() != null && $result->getSecondExpr() == null) {
                $exprToAdd = "";
                while(!$this->stack->isEmpty() && $this->stack->top() != "(") {
                    $exprToAdd = $this->stack->pop() . $exprToAdd;
                }
                $result->setSecondExpr(new RawValueExpression($exprToAdd));

        //if all the terms are full then just return the result
        } else if ($result->getFirstExpr() != null && $result->getSecondExpr() != null && $result->getOperator() != null) {
            //do nothing - just makes sure that this correct case does not throw exceptions

        //if there is a null term then throw a parse exception
        } else {
            throw new ExpressionParseException("Some values missing in expression : " . $result->getFirstExpr() . " " . $result->getOperator() . " " . $result->getSecondExpr());
        }

        return $result;
	}


	function parseThen() {
        /*
         * Gets the statement to do after the then
         */
        $nextInstruction = $this->getType();

        /**
         * The object to be returned
         */
        $resultObject

        switch($nextInstruction) {

            //if it is another if statement
            case $this->linestarts[0] :
                try {
                    $resultObject = $this->parseIf();
                } catch(ExpressionParseException $e) {
                    throw new ExpressionParseException("Parser failed to parse IF statement - check syntax");
                }
                break;

            //if it is a variable declaration
            case $this->linestarts[3] :
                try {
                    $resultObject = $this->parseVariable();
                } catch(ExpressionParseException $e) {
                    throw new ExpressionParseException("Parser failed to parse IF statement - check syntax");
                }
                break;

            //if it is a return statement
            case $this->linestarts[4] :
                try {
                    $resultObject = $this->parseReturn();
                } catch(ExpressionParseException $e) {
                    throw new ExpressionParseException("Parser failed to parse IF statement - check syntax");
                }
                break;
            default :
                throw new ExpressionParseException("Invalid instruction following the THEN statement");
        }

        return $resultObject;
	}

	function parseElse() {

	}

    function parseReturn() {

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
