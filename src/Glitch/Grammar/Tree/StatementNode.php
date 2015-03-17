<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

interface StatementNode
{
    public function invoke(ActivationObject $scope);
}

