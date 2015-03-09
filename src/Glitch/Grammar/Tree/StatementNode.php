<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;

interface StatementNode
{
    public function invoke(ActivationObject $scope);
}

