<?php

namespace contour\parser\evaluators;

use contour\parser\exceptions\ExpressionEvaluationException;
use contour\parser\expressions\iExpression;
use contour\parser\expressions\ParamsExpression;
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
     * @returns mixed
     * Whatever the result of the function might be.
     */
    public function evaluate($function, $vars, $params) {
        //set the instrPtr to 1, as the first instr should be a params expression for parameters, which will be dealt with
        //separately beforehand
        $this->instrPtr = 1;

        //while there are still instructions in the function array to be processed
        //or while result is still null
        //set the result as the evaluation of the current function.
        //do this until all instructions have been evaluated.
        $endOfFunction = count($function) == 0;

        $result = null;

        if (!($function[0] instanceof ParamsExpression))
            throw new ExpressionEvaluationException("No params statement found");
        else {
            $this->setParams($function[0], $vars, $params);
        }

        while(!$endOfFunction && $result == null ) {
            $result = $function[$this->instrPtr]->evaluate($vars);
            $this->instrPtr++;
            if($this->instrPtr >= count($function)) {
                $endOfFunction = true;
            }
        }

        return $result;
    }

    /**
     * @param $paramsLine ParamsExpression
     * @param $vars VariableMap
     * @param $params
     */
    function setParams($paramsLine, $vars, $params) {

        $paramKeys = $paramsLine->evaluate($vars);

        for($i = 0; $i < count($params); $i++) {
            $vars->setVariable($paramKeys[$i], $params[$i]);
        }
    }

}
