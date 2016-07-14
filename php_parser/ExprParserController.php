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

      /**
       * @var iExpression[]
       */
      $function = [];
  
      /**
       *The line that has been parsed.
       */
      $parsedLine = null;

    for($i = 0; $i < count($this->linesToParse); $i++) {
        try {
            $parsedLine = $this->exprParser.parse($this->linesToParse);
        } catch (ExpressionParseException $e) {
            throw new Exception("Parsing issue on line " . strval($i + 1) ." : " . $e->getMessage());
        }
    }
  }

}
