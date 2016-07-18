<?php

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 12/07/2016
 * Time: 16:23
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