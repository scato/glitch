<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\StringValue;

class StringNode extends NullaryExpressionNode
{
    public function reduce(ActivationObject $scope)
    {
        eval("\$value = {$this->value};");

        return new StringValue($value);
    }
}
