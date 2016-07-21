<?php

namespace contour\parser\expressions;
use contour\parser\expressions\iExpression;
use contour\parser\VariableMap;

/**
 * Class VariableDeclarationExpression
 * @package bnjhope\php_parser\expressions
 */
class VariableDeclarationExpression implements iExpression {

    /**
     * @var iExpression
     */
    private $identifier;

    /**
     * @var iExpression
     */
    private $value;

    public static function withValues($key, $value) {
        $instance = new self();
        $instance->setIdentifier($key);
        $instance->setValue($value);
        return $instance;
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


    public function evaluate(){
        VariableMap::setVariable($this->identifier->getValue(), $this->value->getValue());
    }

    public function __toString()
    {
        return $this->identifier . " = " . $this->value;
    }
}