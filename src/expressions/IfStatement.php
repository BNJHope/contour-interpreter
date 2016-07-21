<?php

namespace bnjhope\php_parser\expressions;
use bnjhope\php_parser\expressions\BooleanExpression;
use bnjhope\php_parser\exceptions\ExpressionEvaluationException;
use bnjhope\php_parser\expressions\ElseExpression;
use bnjhope\php_parser\expressions\iExpression;
use bnjhope\php_parser\expressions\ThenExpression;

/**
 * Class IfStatement
 * @package bnjhope\php_parser\expressions
 */
class IfStatement implements iExpression
{

    public function __construct() {
        $this->elseConstructors = array();
    }

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
     * @var ElseExpression[]
     * The instructions that are carried out if the boolean expression is determined false
     * Or an else if structure is defined.
     */
    private $elseConstructors;

    /**
     * Returns an if statement instance with the given boolean expression and then expression
     * @param $bool
     * @param $then
     * @return IfStatement
     */
    public static function withIfAndThen($bool,  $then) {
        $instance = new self();
        $instance->setBoolExpression($bool);
        $instance->setThenConstructor($then);
        return $instance;
    }

    /**
     * @return mixed
     */
    public function getElseConstructors()
    {
        return $this->elseConstructors;
    }

    /**
     * @param mixed $elseConstructor
     */
    public function setElseConstructor($elseConstructor)
    {
        $this->elseConstructors = $elseConstructor;
    }

    /**
     * Adds another else constructor to this objects array of else constructors.
     * @param $elseConstructor
     * The else constructor to add to the array.
     */
    public function addToElseConstructors($elseConstructor) {
        array_push($this->elseConstructors, $elseConstructor);
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
        /**
         * The result of the boolean expression in the If statement
         * @var boolean
         * */
        $mainBool = null;

        /**
         * The index of where in the array of else constructors the else evaluator loop is.
         */
        $elseInstrCounter = 0;

        /**
         * The result of one else instruction/
         */
        $elseRes = null;

        try{
            $mainBool = $this->boolExpression->evaluate();
        } catch (ExpressionEvaluationException $e) {
            throw new ExpressionEvaluationException($e);
        }

        if($mainBool)
            return $this->thenConstructor->evaluate();
        else {

            while($elseInstrCounter < count($this->elseConstructors) && $elseRes == null) {
                $elseRes = $this->elseConstructors[$elseInstrCounter]->evaluate();
                $elseInstrCounter++;
            }
        }

        return $elseRes;
    }

    public function __toString()
    {
        //the string formed of all of the else statements in the expression.
        $elseString = "";

        //append all of the else statements as strings to the else string to be printed for this if statement
        foreach($this->elseConstructors as $elseStat) {
            $elseString = $elseString . $elseStat->__toString();
        }

        return $this->boolExpression->__toString() . $this->thenConstructor->__toString() . $elseString;
    }


}