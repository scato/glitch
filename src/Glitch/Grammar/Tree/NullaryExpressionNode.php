<?php

namespace Glitch\Grammar\Tree;

abstract class NullaryExpressionNode implements ExpressionNode
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }
}

