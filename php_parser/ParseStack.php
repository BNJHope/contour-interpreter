<?php

namespace bnjhope\php_parser;

/**
 * Class ParseStack
 * Basic stack class for the parser to store characters used.
 */
class ParseStack {
    /**
     * @var integer
     * Current size of the stack.
     */
	private $size;

    /**
     * @var array
     * The contents of the stack.
     */
	private $stackContents;

    /**
     * ParseStack constructor
     */
    function __construct() {
        $this->stackContents = [];
    }

    /**
     * @return mixed
     * @throws StackEmptyException
     * Pops first element off of the stack.
     */
	public function pop() {
        if(empty($this->stackContents)) {
          throw new StackEmptyException();
        } else {
          $this->size--;
          return array_pop($this->stackContents);
        }
	}

    /**
     * Pushes the given contents onto the top of the stack.
     * @param $contents
     * The contents to be pushed onto the stack.
     */
  public function push($contents) {
      $this->size++;
      array_push($this->stackContents, $contents);
  }

    public function isEmpty() {
        return empty($this->stackContents);
    }

    public function top() {
        if(empty($this->stackContents)) return null;
        else return $this->stackContents[$this->size - 1];
    }
}

class StackEmptyException extends Exception {

}
