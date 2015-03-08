<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;

interface ExpressionNode
{
    public function reduce(ActivationObject $scope);
}

