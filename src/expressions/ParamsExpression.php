<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 21/07/2016
 * Time: 16:01
 */

namespace contour\parser\expressions;


use contour\parser\VariableMap;

class ParamsExpression implements iExpression
{

    /**
     * @var string[]
     */
    private $params;

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * @param $vars VariableMap
     * @return mixed|void
     */
    public function evaluate($vars)
    {
        for($i = 0; $i < count($this->params); $i++) {
            $vars->setVariable($this->params[$i], "");
        }
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
    }

    /**
     * @return \string[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param \string[] $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

}