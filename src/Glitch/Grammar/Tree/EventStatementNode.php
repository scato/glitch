<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

abstract class EventStatementNode implements StatementNode
{
    protected $left;
    protected $right;

    public function __construct($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }
}

