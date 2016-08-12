<?php

namespace contour\parser\evaluators;

use contour\parser\exceptions\ExpressionEvaluationException;
use contour\parser\expressions\iExpression;
use contour\parser\expressions\ParamsExpression;
use contour\parser\expressions\RawValueExpression;
use contour\parser\tests\EvaluatorTest;
use contour\parser\VariableMap;

/**
 * Class ExprEvaluator
 * @package bnjhope\php_parser\evaluators
 */
class ExprEvaluator {

    /**
     * The pointer to the current instruction in the function array.
     * @var integer
     */
    private $instrPtr;

    public function __construct() {
        $this->instrPtr = 1;
    }

    /**
     * Evaluates a group of expressions as one function.
     * @param iExpression[] $function
     * The function that has been parsed as a group of iExpressions.
     * @param $params mixed[]
     * @return mixed Whatever the result of the function might be.
     * Whatever the result of the function might be.
     * @throws ExpressionEvaluationException
     */
    public function evaluate($function, $params) {
        //set the instrPtr to 1, as the first instr should be a params expression for parameters, which will be dealt with
        //separately beforehand
        $this->instrPtr = 1;

        //while there are still instructions in the function array to be processed
        //or while result is still null
        //set the result as the evaluation of the current function.
        //do this until all instructions have been evaluated.
        $endOfFunction = count($function) == 0;

        /**
         * The result to be returned from evaluating the function.
         */
        $result = null;

        /**
         * Throw an error if the first line of the function is not a parameters statement.
         */
        if (!($function[0] instanceof ParamsExpression))
            throw new ExpressionEvaluationException("No params statement found");
        else {

        /**
         * Otherwise, set the parameters of the fu
         */
            $vars = $this->setParams($function[0], $params);
        }

        /**
         * Executes instructions until a result has been returned or until there are no more instructions
         * to execute.
         */
        while(!$endOfFunction && $result == null ) {

            /**
             * Sets the result of the evaluation as the result to be returned.
             * May return null if nothing is yet returned.
             */
            $result = $function[$this->instrPtr]->evaluate($vars);

            /**
             * Get next instruction.
             */
            $this->instrPtr++;

            /**
             * If the instruction pointer has reached its limit
             * then exit the loop.
             */
            if($this->instrPtr >= count($function)) {
                $endOfFunction = true;
            }
        }

        return $result;
    }

    /**
     * Returns variable map for the evaluator to use, with the paramters placed in it.
     * @param $paramsLine ParamsExpression
     * @param $params
     * @return VariableMap
     */
    function setParams($paramsLine, $params) {

        /**
         * The variable map for the evaluator to use.
         */
        $vars = new VariableMap();

        /**
         * Gets the identifiers for the parameters that have been passed to the function
         * so that they can be addressed in the variable map.
         */
        $paramKeys = $paramsLine->evaluate($vars);

        /**
         * For every paramters
         */
        for($i = 0; $i < count($params); $i++) {
            /**
             * Set the parameters for the function and places them in the variable map.
             */
            $vars->setVariable($paramKeys[$i], $params[$i]);
        }

        return $vars;
    }

}
