<?php

namespace contour\parser;

use contour\parser\exceptions\ExpressionEvaluationException;

/**
 * Class VariableMap
 * @package bnjhope\php_parser
 */
class VariableMap
{

    /**
     * The array that maps the identifiers of variables to their values.
     * @var array
     */
    private $variables = array();

    /**
     * Gets the value stored in the variables map at the given key.
     * @param string $key
     * The identifier of the variable to fetch.
     * @return mixed
     * The value stored in the variables map at the given key.
     * @throws ExpressionEvaluationException
     * If there is not a value stored at the given key then this exception is thrown.
     */
    public function getVariable($key)
    {

        if (array_key_exists($key, $this->variables))
            return $this->variables[$key];
        else
            throw new ExpressionEvaluationException("Variable " . $key . " not found.");
    }

    /**
     * Sets a key value mapping in the variables map with the given identifier and value.
     * @param $key
     * The identifier of the variable to be stored.
     * @param $value
     * The value of the variable to be stored.
     */
    public function setVariable($key, $value)
    {
        $this->variables[$key] = $value;
    }

}