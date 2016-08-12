<?php

namespace contour\parser\expressions;

/**
 * Class OperationExpression
 * @package bnjhope\php_parser\expressions
 */
class OperationExpression implements iExpression
{

    /**
     * @var string
     */
    private $operation;

    /**
     * OperationExpression constructor.
     * @param $operation
     */
    public function __construct($operation)
    {
        $this->operation = $operation;
    }

    /**
     * @return mixed
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @param mixed $operation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param \contour\parser\VariableMap $vars
     * @return string
     */
    public function evaluate($vars)
    {
        return $this->operation;
    }

    public function __toString()
    {
        return $this->operation;
    }


}