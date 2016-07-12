<?php

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
     * @var bool
     * Determines whether the stack is empty or not.
     */
    private $isEmpty;

    /**
     * ParseStack constructor
     */
    function __construct() {
        $this->isEmpty = true;
    }

    /**
     * @return mixed
     * @throws StackEmptyException
     * Pops first element off of the stack.
     */
	public function pop() {
        if($this->isEmpty) {
          throw new StackEmptyException();
        } else {
          $this->size--;
            if($this->size == 0) $this->isEmpty = true;
          return array_pop($this->stackContents);
        }
	}

    /**
     * @param $contents The contents to be pushed onto the stack
     * Pushes the given contents onto the top of the stack.
     */
  public function push($contents) {
      $this->size++;
      array_push($this->stackContents, $contents);
      $this->isEmpty = false;
  }

    public function isEmpty() {
        return $this->isEmpty;
    }

    public function top() {
        return $this->contents[$this->size - 1];
    }
}

class StackEmptyException extends Exception {

}
