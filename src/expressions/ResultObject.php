<?php

namespace contour\parser\expressions;
use contour\parser\expressions\iExpression;

/**
 * Class ResultObject
 * @package bnjhope\php_parser\expressions
 */
class ResultObject implements iExpression
{

    /**
     * @var iExpression
     * The result to be returned by the evaluator.
     */
    private $result;

    public static function withValue($value) {
        $instance = new self();
        $instance->setResult($value);
        return $instance;
    }

    /**
     * @param \contour\parser\VariableMap $vars
     * @return mixed
     */
    public function evaluate($vars)
    {

        return $this->result->evaluate($vars);
    }

    public function __toString()
    {
        return "return : " . $this->result;
    }



    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}