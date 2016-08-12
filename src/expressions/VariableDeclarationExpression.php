<?php

namespace contour\parser\expressions;

use contour\parser\VariableMap;

/**
 * Class VariableDeclarationExpression
 * @package bnjhope\php_parser\expressions
 */
class VariableDeclarationExpression implements iExpression
{

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var iExpression
     */
    private $value;

    /**
     * @param $key
     * @param $value
     * @return VariableDeclarationExpression
     */
    public static function withValues($key, $value)
    {
        $instance = new self();
        $instance->setIdentifier($key);
        $instance->setValue($value);
        return $instance;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param VariableMap $vars
     * @return mixed|void
     */
    public function evaluate($vars)
    {
        $vars->setVariable($this->getIdentifier(), $this->value->evaluate($vars));
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->identifier . " = " . $this->value;
    }
}