<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

class FireNode implements StatementNode
{
    protected $left;
    protected $right;

    public function __construct(ExpressionNode $left, array $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    public function invoke(ActivationObject $scope)
    {
        $left = $this->left->reduce($scope);
        $right = array_map(function (ExpressionNode $value) use ($scope) {
            return $value->reduce($scope);
        }, $this->right);

        $left->fire($right);
    }
}
