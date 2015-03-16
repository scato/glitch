<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;
use Glitch\Interpreter\StringValue;

class StringNode extends NullaryExpressionNode
{
    public function reduce(ActivationObject $scope)
    {
        eval("\$value = {$this->value};");

        return new StringValue($value);
    }
}
