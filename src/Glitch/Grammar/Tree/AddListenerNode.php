<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;

class AddListenerNode implements StatementNode
{
    private $left;
    private $right;

    public function __construct($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function getLeft()
    {
        return $this->left;
    }

    public function getRight()
    {
        return $this->right;
    }

    public function invoke(ActivationObject $scope)
    {
        $left = $this->left->reduce($scope);
        $right = $this->right->reduce($scope);

        $left->addListener($right);
    }
}

