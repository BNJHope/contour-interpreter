<?php

namespace bnjhope\php_parser\expressions;
use bnjhope\php_parser\expressions\iExpression;

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 11/07/2016
 * Time: 10:57
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

    public function evaluate()
    {

        return $this->result->evaluate();;
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