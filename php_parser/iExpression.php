<?php

namespace bnjhope\php_parser;

/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 12/07/2016
 * Time: 09:07
 */
interface iExpression {
    public function evaluate();
    public function __toString();
}