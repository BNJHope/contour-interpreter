<?php
/**
 * Created by PhpStorm.
 * User: bnjhope
 * Date: 8/8/16
 * Time: 1:40 PM
 */

namespace contour\parser\parsers;

use contour\parser\expressions\OperationExpression;

class ArithmeticStack {

    private $boolExprs = ["=", ">", "<", "<=", ">="];

    private $arithmeticExprs = ["+", "-", "*", "/"];

    private $stack = [];

    function push($content){
        array_push($this->stack, $content);
    }

    function pop() {
        return array_pop($stack);
    }

    function isEmpty() {
        return empty($this->stack);
    }

    function top() {
        if($this->stack)
            return $this->stack(count($this->stack) - 1);
        else
            throw new ExpressionParseException("Invalid Assignments");
    }

    function evaluate() {
        $totalExpr = new ArithmeticExpression();

        $current = $this->pop();

        $totalExpr->setOperator(new OperationExpression($current));

        $exprToAdd = null;

        $current = $this->top();

        if ($this->isOperator($current)) {
            $exprToAdd = $this->evaluate();
        } else {
            $exprToAdd = new RawValueExpression($this->pop());
        }

        $totalExpr->setSecondExpr($exprToAdd);

        $current = $this->top();

        if($this->isOperator($current)) {
            $exprToAdd = $this->evaluate();
        } else {
            $exprToAdd = new RawValueExpression($this->pop());
        }

        $totalExpr->setFirstExpr($exprToAdd);

        return $totalExpr;
    }

    function isOperator($expr) {
        return ($expr instanceof OperationExpression);
    }


}