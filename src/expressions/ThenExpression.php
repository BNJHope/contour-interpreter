<?php

namespace contour\parser\expressions;
use contour\parser\expressions\iExpression;

/**
 * Class ThenExpression
 * @package bnjhope\php_parser\expressions
 */
class ThenExpression implements iExpression
{

    /**
     * @var iExpression
     * The expression that will occur after the then statement.
     */
    private $subExpression;

    public static function withValue($value) {
        $instance = new self;
        $instance->setSubExpression($value);
        return $instance;
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

    public function evaluate()
    {
        return $this->subExpression->evaluate();
    }

    public function __toString()
    {
        return "THEN " . $this->subExpression->__toString();
    }

}