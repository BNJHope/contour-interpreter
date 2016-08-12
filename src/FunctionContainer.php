<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 21/07/2016
 * Time: 13:52
 */

namespace contour\parser;

use contour\parser\expressions\iExpression;

/**
 * Container for a function tree to be stored in a database.
 * Contains a tree to represent the function which can be evaluated and also contains a variable map for the function to use.
 * Class FunctionContainer
 * @package contour\parser
 */
class FunctionContainer
{

    /**
     * The tree representation of the saved function.
     * @var iExpression[]
     */
    private $functionTree;

    public function __construct($functionTree)
    {
        $this->functionTree = $functionTree;
    }

    /**
     * @return expressions\iExpression[]
     */
    public function getFunctionTree()
    {
        return $this->functionTree;
    }

    /**
     * @param expressions\iExpression[] $functionTree
     */
    public function setFunctionTree($functionTree)
    {
        $this->functionTree = $functionTree;
    }

}