<?php

namespace bnjhope\php_parser;

use bnjhope\php_parser\evaluators\ExprEvaluator;
use bnjhope\php_parser\exceptions\ExpressionEvaluationException;
use bnjhope\php_parser\exceptions\ExpressionParseException;
use bnjhope\php_parser\parsers\ExprParserController;

/**
 * Class FunctionParser Overall class for managing parsing and evaluating functions passed to it.
 * @package bnjhope\php_parser
 */
class FunctionParser
{

    private $parser;

    private $evaluator;

    public function __construct() {
        $this->parser = new ExprParserController();
        $this->evaluator = new ExprEvaluator();
    }

    /**
     * Resolves a given function and returns a result from parsing and evaluating.
     * @param string $functionString
     * The function to be resolved.
     * @return mixed|string
     */
    public function resolve($functionString) {
        $functionExprs = array();

        try {
            $functionExprs = $this->parser->parse($functionString);
        } catch (ExpressionParseException $e) {
            return $e->getMessage();
        }

        try {
            $result = $this->evaluator->evaluate($functionExprs);
        } catch (ExpressionEvaluationException $e) {
            return $e->getMessage();
        }

        return $result;
    }

}