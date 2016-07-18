<?php

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 12/07/2016
 * Time: 09:16
 */
class RawValueExpression implements iExpression
{
    /**
     * @var string
     * The raw value stored in this expression.
     */
    private $value;

    function __construct($value) {
        $this->value = $value;
    }

    public function __toString()
    {
       return $this->value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Determines the type of the raw value and returns it.
     */
    public function evaluate()
    {
        switch(true) {
            case (substr($this->value, -1) == "\"") && (substr($this->value, 0) == "\"") :
                return $this->value;
            //if it scans correctly as an integer
            case (sscanf($this->value, "%d")) :
                return intval($this->value);

            //if it scans correctly as a floating point number
            case (sscanf($this->value, "%f")) :
                return floatval($this->value);

            //if the value is true
            case($this->value == "true") :
                return true;

            //if the value is false
            case ($this->value == "false") :
                return false;

            //otherwise check to see if it is a variable name
            default :
                try {

                } catch (ExpressionEvaluationException $e) {
                    throw new ExpressionEvaluationException($e);
                }
        }
    }

}