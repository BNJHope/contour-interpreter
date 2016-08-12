<?php
/**
 * Created by PhpStorm.
 * User: bnjhope
 * Date: 8/8/16
 * Time: 1:40 PM
 */

namespace contour\parser\parsers;

use contour\parser\exceptions\ExpressionParseException;
use contour\parser\expressions\BooleanExpression;
use contour\parser\expressions\iExpression;
use contour\parser\expressions\OperationExpression;
use contour\parser\expressions\RawValueExpression;

class ExpressionTreeConstructor {

    /**
     * @var iExpression[]
     */
    private $stack;

    /**
     * ExpressionTreeConstructor constructor.
     * @param \contour\parser\expressions\iExpression[] $stack
     */
    public function __construct(array $stack)
    {
        $this->stack = $stack;
    }

    /**
     * Performs a stack pop operation on the array of expressions.
     * @return mixed The expression on the top of the stack.
     * The expression on the top of the stack.
     * @throws ExpressionParseException
     * This exception is thrown if the stack is empty.
     */
    function pop() {
        if(!$this->isEmpty())
            return array_pop($this->stack);
        else
            throw new ExpressionParseException("Stack empty");
    }

    /**
     * Returns true or false depending on whether there are any elements left in the stack or not.
     * @return bool
     */
    function isEmpty() {
        return empty($this->stack);
    }

    /**
     * Returns the expression at the top of the stack
     * without popping it off of the stack.
     * @return iExpression
     * The expression at the top of the stack.
     * @throws ExpressionParseException
     * This exception is thrown if the stack is empty.
     */
    function top() {
        if(!$this->isEmpty())
            return $this->stack[count($this->stack) - 1];
        else
            throw new ExpressionParseException("Invalid Assignments");
    }

    /**
     * Converts the postfix array into a tree of expressions to be evaluated in correct order.
     * @return BooleanExpression
     */
    function convertArrayToTree() {



        /**
         * The expression at the top of the stack.
         */
        $current = $this->pop();

        /**
         * If the stack has only one value and it is not an operation then return that value.
         */
        if(!$this->isOperator($current)){

            $totalExpr = $current;

        /**
         * If there are more than one expressions in the stack and the first one is an operation
         * then set the next expression in the tree to that operation and recursively get the other values
         * for the tree.
         */
        } else {

            /**
             * The overall expression to be formed and returned for evaluation.
             */
            $totalExpr = new BooleanExpression();

            /**
             * Set the operator of the total expression to the operation
             */
            $totalExpr->setOperator($current);

            /**
             * Get the next expression and set it to the second expression of this boolean expression
             * as the stack works with the most recently parsed expressions, so the second one
             * will come before the first in the stack.
             */
            $totalExpr->setSecondExpr($this->getNextExpression());

            /**
             * Get the next expression and set it to the first.
             */
            $totalExpr->setFirstExpr($this->getNextExpression());
        }

        /**
         * Return the total expression so that it can be added to the tree and evaluated.
         */
        return $totalExpr;
    }

    /**
     * Determines whether a given expression is an operation or not.
     * @param $expr iExpression
     * The expression to evaluate.
     * @return bool
     * Returns true if the expression is an operation, false if not.
     */
    function isOperator($expr) {
        return ($expr instanceof OperationExpression);
    }

    /**
     * Gets the next expression on the stack.
     * @return iExpression
     */
    function getNextExpression() {
        /**
         * Checks the top of the stack to see what type of expression it is.
         */
        $current = $this->top();

        /**
         * If it is an operator, then evaluate the rest of that expression recursively.
         */
        if($this->isOperator($current)) {
            $exprToAdd = $this->convertArrayToTree();

        /**
         * Otherwise, add the single expression to the tree.
         */
        } else {
            $exprToAdd = $this->pop();
        }

        /**
         * Returns the expression that was found.
         */
        return $exprToAdd;
    }
}