<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

class CallNode implements ExpressionNode
{
    protected $left;
    protected $right;

    public function __construct(ExpressionNode $left, array $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function reduce(ActivationObject $scope)
    {
        $left = $this->left->reduce($scope);
        $right = array_map(function (ExpressionNode $value) use ($scope) {
            return $value->reduce($scope);
        }, $this->right);

        return $left->call($right);
    }
}
