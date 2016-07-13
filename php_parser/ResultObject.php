<?php

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 11/07/2016
 * Time: 10:57
 */
class ResultObject implements iExpression
{
    public function evaluate()
    {
        // TODO: Implement evaluate() method.
    }

    public function __toString()
    {
        return "return : " . $this->result;
    }

    /**
     * @var string
     * The result to be returned by the evaluator.
     */
    private $result;

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