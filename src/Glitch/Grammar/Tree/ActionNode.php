<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActionValue;
use Glitch\Runtime\ActivationObject;

class ActionNode implements ExpressionNode
{
    private $parameters;
    private $statements;

    public function __construct($parameters, $statements)
    {
        $this->parameters = $parameters;
        $this->statements = $statements;
    }

    public function reduce(ActivationObject $scope)
    {
        return new ActionValue($this->parameters, $this->statements, $scope);
    }
}
