<?php

namespace contour\parser\expressions;
use contour\parser\expressions\iExpression;

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

    /**
     * @param \contour\parser\VariableMap $vars
     * @return mixed
     */
    public function evaluate($vars)
    {
        return $this->subExpression->evaluate($vars);
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