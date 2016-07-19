<?php

namespace bnjhope\php_parser;

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 18/07/2016
 * Time: 10:51
 */
class VariableMap
{

    /**
     * The array that maps the identifiers of variables to their values.
     * @var array
     */
    private static $variables = array();

    /**
     * Gets the value stored in the variables map at the given key.
     * @param string $key
     * The identifier of the variable to fetch.
     * @return mixed
     * The value stored in the variables map at the given key.
     * @throws ExpressionEvaluationException
     * If there is not a value stored at the given key then this exception is thrown.
     */
    public static function getVariable($key) {
        if(array_key_exists($key, self::$variables))
            return self::$variables[$key];
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
    public static function setVariable($key, $value) {
        self::$variables[$key] = $value;
    }

}