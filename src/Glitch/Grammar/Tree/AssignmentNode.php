<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

class AssignmentNode implements StatementNode
{
    private $name;
    private $value;

    public function __construct($name, ExpressionNode $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function invoke(ActivationObject $scope)
    {
        $scope->set($this->name, $this->value->reduce($scope));
    }
}
