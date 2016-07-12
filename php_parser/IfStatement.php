<?php

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 12/07/2016
 * Time: 16:37
 */
class IfStatement implements iExpression
{

    /**
     * @var BooleanExpression
     * The boolean expression that determines whether the if condition is true or false.
     */
    private $boolExpression;

    /**
     * @var ThenExpression
     * The instructions that are carried out if the boolean expression is determined true.
     */
    private $thenConstructor;

    /**
     * @var ElseExpression
     * The instructions that are carried out if the boolean expression is determined false
     * Or an else if structure is defined.
     */
    private $elseConstructor;

    /**
     * @return mixed
     */
    public function getElseConstructor()
    {
        return $this->elseConstructor;
    }

    /**
     * @param mixed $elseConstructor
     */
    public function setElseConstructor($elseConstructor)
    {
        $this->elseConstructor = $elseConstructor;
    }

    /**
     * @return mixed
     */
    public function getThenConstructor()
    {
        return $this->thenConstructor;
    }

    /**
     * @param mixed $thenConstructor
     */
    public function setThenConstructor($thenConstructor)
    {
        $this->thenConstructor = $thenConstructor;
    }


    /**
     * @return mixed
     */
    public function getBoolExpression()
    {
        return $this->boolExpression;
    }

    /**
     * @param mixed $boolExpression
     */
    public function setBoolExpression($boolExpression)
    {
        $this->boolExpression = $boolExpression;
    }

    public function evaluate()
    {
        // TODO: Implement evaluate() method.
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
    }


}