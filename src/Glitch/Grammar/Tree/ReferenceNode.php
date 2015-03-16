<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;

class ReferenceNode extends NullaryExpressionNode
{
    public function reduce(ActivationObject $scope)
    {
        return $scope->get($this->value);
    }
}
