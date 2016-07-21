<?php

namespace bnjhope\php_parser\expressions;
use bnjhope\php_parser\expressions\iExpression;

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 14/07/2016
 * Time: 08:49
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