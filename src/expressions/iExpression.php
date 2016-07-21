<?php

namespace contour\parser\expressions;
use contour\parser\VariableMap;

/**
 * Interface iExpression
 * @package bnjhope\php_parser\expressions
 */
interface iExpression {

    /**
     * Function to evaluate an expression and return the result from evaluating the expression.
     * @param $vars VariableMap
     * The map of variables that the functions may use.
     * @return mixed
     */
    public function evaluate($vars);

    /**
     * Makes sure a function can be put in a string to enable printing of errors.
     * @return mixed
     */
    public function __toString();
}