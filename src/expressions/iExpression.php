<?php

namespace bnjhope\php_parser\expressions;

/**
 * Interface iExpression
 * @package bnjhope\php_parser\expressions
 */
interface iExpression {
    public function evaluate();
    public function __toString();
}