<?php

namespace bnjhope\php_parser\expressions;
use bnjhope\php_parser\expressions\iExpression;

/**
 * Class ElseExpression
 * @package bnjhope\php_parser\expressions
 */
class ElseExpression implements iExpression
{

    /**
     * @var iExpression
     * The expression that follows the else statement.
     */
    private $subExpression;

    public function evaluate()
    {
        return $this->subExpression->evaluate();
    }

    public function __toString()
    {
       return "ELSE : " . $this->subExpression->__toString();
    }

    /**
     * @return iExpression
     */
    public function getSubExpression()
    {
        return $this->subExpression;
    }

    /**
     * @param iExpression $subExpression
     */
    public function setSubExpression($subExpression)
    {
        $this->subExpression = $subExpression;
    }


}