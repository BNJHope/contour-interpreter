<?php

class ArithmeticExpression implements iExpression {

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
 * @return iExpression The result of the two images.
 */
  public function evaluate(){

    /**
     * The arithmetic result to be returned from evaluating this arithmetic expression.
     * @var int
     */
    $result = new RawValueExpression();

    switch ($this->operator->evaluate()) {
      case 'value':
        # code...
        break;

      default:
        # code...
        break;
    }

  }

  public function __toString(){

  }

}
