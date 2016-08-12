<?php

namespace contour\parser\expressions;
use contour\parser\exceptions\ExpressionEvaluationException;
use contour\parser\expressions\iExpression;
use contour\parser\VariableMap;

/**
 * Class RawValueExpression
 * @package bnjhope\php_parser\expressions
 */
class RawValueExpression implements iExpression
{
    /**
     * @var string
     * The raw value stored in this expression.
     */
    private $value;

    function __construct($value) {
        $this->value = $value;
    }

    public function __toString()
    {
       return $this->value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Determines the type of the raw value and returns it.
     * @param $vars VariableMap
     * @return bool|mixed
     * @throws ExpressionEvaluationException
     */
    public function evaluate($vars)
    {
        switch(true) {
            case (substr($this->value, -1) == "\"") && (substr($this->value, 0, 1) == "\"") :
                return $this->value;
            //if it scans correctly as an integer
            case (is_numeric($this->value)) :
                return floatval($this->value);

            //if the value is true
            case($this->value == "true") :
                return true;

            //if the value is false
            case ($this->value == "false") :
                return false;

            //otherwise check to see if it is a variable name
            default :
                try {
                    return $vars->getVariable($this->value);
                } catch (ExpressionEvaluationException $e) {
                    throw new ExpressionEvaluationException($e);
                }
        }
    }

}