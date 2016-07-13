<?php
include 'iExpression.php';

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 12/07/2016
 * Time: 09:06
 */
class BooleanExpression implements iExpression {

    /**
     * @var iExpression
     * The first expression in the boolean expression.
     */
    private $firstExpr;

    /**
     * @var iExpression
     * The second expression in the boolean expression.
     */
    private $secondExpr;

    /**
     * @var iExpression
     * The operator that the expression will operate on its two sub expressions with.
     */
    private $operator;

    public static function withValues(iExpression $firstExpr, iExpression $operator = null, iExpression $secondExpr = null) {
        $instance = new self();
        $instance->setFirstExpr($firstExpr);
        $instance->setOperator($operator);
        $instance->setSecondExpr($secondExpr);
        return $instance;
    }

    /**
     * @return iExpression
     */
    public function getFirstExpr()
    {
        return $this->firstExpr;
    }

    /**
     * @param iExpression $firstExpr
     */
    public function setFirstExpr($firstExpr)
    {
        $this->firstExpr = $firstExpr;
    }

    /**
     * @return iExpression
     */
    public function getSecondExpr()
    {
        return $this->secondExpr;
    }

    /**
     * @param iExpression $secondExpr
     */
    public function setSecondExpr($secondExpr)
    {
        $this->secondExpr = $secondExpr;
    }

    /**
     * @return iExpression
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param iExpression $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    public function __toString()
    {
        return $this->firstExpr->__toString() . " " . $this->operator . " " . $this->secondExpr->__toString();
    }


    public function evaluate(){

    }
}