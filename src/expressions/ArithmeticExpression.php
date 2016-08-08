<?php

use contour\parser\exceptions\ExpressionEvaluationException;
use contour\parser\expressions\iExpression;
use contour\parser\expressions\OperationExpression;
use contour\parser\expressions\RawValueExpression;

class ArithmeticExpression implements iExpression
{

    /**
     * The first expression in the total arithmetic expression
     * @var iExpression
     */
    private $firstExpr;

    /**
     * The operation to be performed on the other two expressions
     * contained in this arithmetic expression.
     * @var OperationExpression
     */
    private $operator;

    /**
     * The second expression in the total arithmetic expression
     * @var iExpression
     */
    private $secondExpr;

    /**
     * Evaluates the arithmetic expression and returns it.
     * @param \contour\parser\VariableMap $vars
     * @return iExpression The result of the two images.
     * @throws ExpressionEvaluationException
     */
    public function evaluate($vars)
    {

        /**
         * The arithmetic result to be returned from evaluating this arithmetic expression.
         * @var RawValueExpression
         */
        $result = new RawValueExpression($vars);

        switch ($this->operator->evaluate($vars)) {
            case "+":
                $result->setValue($this->firstExpr->evaluate($vars)->getValue() + $this->secondExpr->evaluate($vars)->getValue());
                break;
            case "*" :
                $result->setValue($this->firstExpr->evaluate($vars)->getValue() * $this->secondExpr->evaluate($vars)->getValue());
                break;
            case "-" :
                $result->setValue($this->firstExpr->evaluate($vars)->getValue() - $this->secondExpr->evaluate($vars)->getValue());
                break;
            case "/" :
                $result->setValue($this->firstExpr->evaluate($vars)->getValue() / $this->secondExpr->evaluate($vars)->getValue());
                break;
            default :
                throw new ExpressionEvaluationException("Invalid operator " . $this->operator);
                break;
        }

        return $result;

    }

    public function __toString()
    {
        return $this->firstExpr->__toString() . $this->operator->__toString() . ($this->secondExpr == null ? "" : $this->secondExpr->__toString());
    }

}
