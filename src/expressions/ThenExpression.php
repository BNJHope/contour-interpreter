<?php

namespace contour\parser\expressions;

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

    public static function withValue($value)
    {
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
        return "THEN " . $this->subExpression->__toString();
    }

}