<?php

namespace bnjhope\php_parser\expressions;
use bnjhope\php_parser\expressions\iExpression;

/**
 * Class TagExpression
 * @package bnjhope\php_parser\expressions
 */
class TagExpression implements iExpression
{
    /**
     * @var string
     * The tag stored by the expression.
     */
    private $tag;

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function evaluate()
    {
        // TODO: Implement evaluate() method.
    }

    public function __toString()
    {
        return "#(" . $this->tag . ")";
    }

    public static function withValues($tag){
        $instance = new self();
        $instance->setTag($tag);
        return $instance;
    }
}