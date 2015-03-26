<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

class TernaryNode implements ExpressionNode
{
    private $first;
    private $second;
    private $third;

    public function __construct(ExpressionNode $first, ExpressionNode $second, ExpressionNode $third)
    {
        $this->first = $first;
        $this->second = $second;
        $this->third = $third;
    }

    public function reduce(ActivationObject $scope)
    {
        if ($this->first->reduce($scope)->toBoolean()) {
            return $this->second->reduce($scope);
        } else {
            return $this->third->reduce($scope);
        }
    }
}
