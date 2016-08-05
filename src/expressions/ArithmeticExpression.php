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

  public function evaluate(){

  }

  public function __toString(){

  }

}
