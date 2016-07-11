<?php
class ParseStack {
	private $stackLevel;
	private $stackContents[];

	public function pop() throws StackEmptyException {
    if($this->stackLevel <= 0) {
      throw new StackEmptyException($error);
    } else {
      $this->stackLevel--;
      return array_pop($this->stackContents);
    }
	}

  public function push($contents) {
      $this->stackLevel++;
      array_push($this->stackContents, $contents);
  }
}

class StackEmptyException extends Exception {

}
