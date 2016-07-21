<?php

namespace contour\parser\expressions;

use contour\parser\exceptions\ExpressionEvaluationException;
/**
 * Class BooleanExpression
 * @package bnjhope\php_parser\expressions
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


    /**
     * Evaluates the boolean expression
     * @return bool
     * The result from evaluating the boolean expression.
     * @throws ExpressionEvaluationException
     * If there is an evaluation error then throw this over to the calling method.
     */
    public function evaluate(){

        //try and get the first side of the expression
        $leftHandSide = $this->firstExpr->evaluate();
        $string = "";

        //if there is only a left hand side expression
        if($this->operator == null) {

            //return the value as a boolean expression whatever the type of the left hand side is.
            return ($leftHandSide || false);

        } else {

            //get the second expression if it exists
            $rightHandSide = $this->secondExpr->evaluate();

            //return true or false depending on the two values
            switch ($this->operator->evaluate()) {
                case "&" :
                    return $leftHandSide && $rightHandSide;
                case "|" :
                    return $leftHandSide || $rightHandSide;
                case "=" :
                    return $leftHandSide == $rightHandSide;
                case ">" :
                    return $leftHandSide > $rightHandSide;
                case "<" :
                    return $leftHandSide < $rightHandSide;
                case "and" :
                    return $leftHandSide && $rightHandSide;
                case "or" :
                    return $leftHandSide || $rightHandSide;
                default :
                    throw new ExpressionEvaluationException("Invalid operator " . $this->operator . " between " . $this->firstExpr . " and " . $this->secondExpr);
            }
        }
    }
}