<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

abstract class StatementNode
{
    protected $left;
    protected $right;

    public function __construct($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    abstract public function invoke(ActivationObject $scope);
}

