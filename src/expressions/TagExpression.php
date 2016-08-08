<?php

namespace contour\parser\expressions;

use contour\parser\expressions\iExpression;

/**
 * Class TagExpression
 * @package bnjhope\php_parser\expressions
 */
class TagExpression implements iExpression
{
    /**
     * @var string[]
     * The tag stored by the expression.
     */
    private $tags;

    /**
     * @return \string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param \string[] $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param \contour\parser\VariableMap $vars
     * @return mixed|void
     */
    public function evaluate($vars)
    {
        // TODO: Implement evaluate() method.
    }

    public function __toString()
    {
        /**
         * @var string
         */
        $tagString = "";

        for ($i = 0; $i < count($this->tags); $i++) {
            $tagString .= $this->tags[$i];
            if ($i < count($this->tags) - 1)
                $tagString .= ",";
        }

        return "#(" . $tagString . ")";
    }

    public static function withValues($tags)
    {
        $instance = new self();
        $instance->setTags($tags);
        return $instance;
    }
}