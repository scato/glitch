<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;
use Glitch\Interpreter\StringValue;

class StringNode implements ExpressionNode
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
        eval("\$value = {$this->value};");

        return new StringValue($value);
    }
}
