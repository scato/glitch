<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;

class FireNode extends StatementNode
{
    public function invoke(ActivationObject $scope)
    {
        $left = $this->left->reduce($scope);
        $right = $this->right->reduce($scope);

        $left->fire($right);
    }
}
