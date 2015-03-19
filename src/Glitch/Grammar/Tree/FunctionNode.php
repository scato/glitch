<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\FunctionValue;
use Glitch\Runtime\ActivationObject;

class FunctionNode implements ExpressionNode
{
    private $parameters;
    private $expression;

    public function __construct(array $parameters, ExpressionNode $expression)
    {
        $this->parameters = $parameters;
        $this->expression = $expression;
    }

    public function reduce(ActivationObject $scope)
    {
        return new FunctionValue($this->parameters, $this->expression, $scope);
    }
}
