<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

class RemoveListenerNode extends EventStatementNode
{
    public function invoke(ActivationObject $scope)
    {
        $left = $this->left->reduce($scope);
        $right = $this->right->reduce($scope);

        $left->removeListener($right);
    }
}
