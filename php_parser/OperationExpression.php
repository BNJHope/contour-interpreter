<?php

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 13/07/2016
 * Time: 13:50
 */
class OperationExpression implements iExpression
{

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

    public function evaluate()
    {
        // TODO: Implement evaluate() method.
    }

    public function __toString()
    {
        return $this->operation;
    }


}