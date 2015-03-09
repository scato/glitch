<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;

class ReferenceNode implements ExpressionNode
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function reduce(ActivationObject $scope)
    {
        return $scope->get($this->value);
    }
}
