<?php

class ExprParser {

	$openBracket = '(';
	$closeBracket = ')';

	$linestarts = ('if', 'then', 'else', 'let');
	$boolCompare = ('=', '>', '<');
	$booleanExprs = ('&', '|', '!');
	$variableDeclarationMatch = "[A-Za-z0-9]*";

	function parse(string $expr) {

		$parseSuccess = true;

		$expressionType = strtolower(strtok($expr,  ' '));
		switch($expressionType) {
			case $this->linestarts[0] :
				try {
					$parseSuccess = $this->parseIf($expr);
				} catch(ExpressionParseException $e) {
					$parseSuccess = false;
				}
				break;
			case $this->linestarts[1] :
				try {
					$parseSuccess = $this->parseThen($expr);
				} catch(ExpressionParseException $e) {
					$parseSuccess = false;
				}
				break;
			case $this->linestarts[2] :
				try {
					$parseSuccess = $this->parseElse($expr);
				} catch(ExpressionParseException $e) {
					$parseSuccess = false;
				}
				break;
			case $this->linestarts[3] :
				try {
					$parseSuccess = $this->parseVariable($expr);
				} catch(ExpressionParseException $e) {
					$parseSuccess = false;
				}
				break;
		}


	}

	function parseIf(string $expr) throws ExpressionParseException {

	}

	function parseThen(string $expr) throws ExpressionParseException{

	}

	function parseVariable(string $expr)throws ExpressionParseException {

	}

	function parseElse(string $expr) throws ExpressionParseException {

	}

	function parseBool() throws ExpressionParseException {

	}
}

class ParentExpression {


}

class BooleanExpression {

	$firstExpr = "";

	$secondExpr = "";

	$operator = "";
}


class VariableDeclaration {

	$identifier = "";

	$value = "";

}

class ExpressionParseException extends Exception {

}

class ParseStack {

	private stackLevel;
	private stackContents;

	function pop(){

	}

  function push($contents) {
      $this->stackLevel++;
      array_push();

  }
}
