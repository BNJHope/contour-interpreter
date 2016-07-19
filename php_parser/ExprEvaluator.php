<?php

namespace bnjhope\php_parser;

class ExprEvaluator {

    /**
     * The pointer to the current instruction in the function array.
     * @var integer
     */
    private $instrPtr;

    public function __construct() {
        $this->instrPtr = 0;
    }

    /**
     * Evaluates a group of expressions as one function.
     * @param iExpression[] $function
     * The function that has been parsed as a group of iExpressions.
     * @returns mixed
     * Whatever the result of the function might be.
     */
    public function evaluate($function) {

        //while there are still instructions in the function array to be processed
        //or while result is still null
        //set the result as the evaluation of the current function.
        //do this until all instructions have been evaluated.
        $endOfFunction = count($function) == 0;

        $result = null;

        while(!$endOfFunction && $result == null ) {
            $result = $function[$this->instrPtr++]->evaluate();
        }

        return $result;
    }

}
