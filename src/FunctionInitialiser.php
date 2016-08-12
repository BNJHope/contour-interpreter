<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 22/07/2016
 * Time: 15:34
 */

namespace contour\parser;


class FunctionInitialiser
{

    /**
     * @var string[]
     */
    private $locationOfFunc;

    /**
     * @var mixed[]
     */
    private $params;

    /**
     * FunctionInitialiser constructor.
     * @param \string[] $locationOfFunc
     * @param \mixed[] $params
     */
    public function __construct(array $locationOfFunc, array $params)
    {
        $this->locationOfFunc = $locationOfFunc;
        $this->params = $params;
    }

    /**
     * @return FunctionContainer
     */
    function getFunction()
    {

    }

}