<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

interface ExpressionNode
{
    public function reduce(ActivationObject $scope);
}

