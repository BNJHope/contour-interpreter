<?php

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 12/07/2016
 * Time: 16:14
 */
class ThenExpression implements iExpression
{
    /**
     * @var iExpression
     * The expression that will occur after the then statement.
     */
    private $subExpression;

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
        // TODO: Implement evaluate() method.
    }

    public function __toString()
    {
        return "THEN " . $this->subExpression->__toString();
    }

}