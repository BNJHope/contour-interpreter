<?php

class ExprParserController {

  public function __construct($stringToParse) {
        $this->stringToParse = $stringToParse;
        $this->linesToParse = explode("\n",$stringToParse);
        $this->exprParser = new ExprParser();
  }

  private $stringToParse;

  private $linesToParse;

  private $exprParser;

  public function parse() {
    for($i = 0; $i < count($this->linesToParse); $i++) {
        try {
            $this->exprParser.parse($this->linesToParse);
        } catch (ExpressionParseException $e) {
            
        }
    }
  }

}
