<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;

class AddListenerNode extends StatementNode
{
    public function invoke(ActivationObject $scope)
    {
        $left = $this->left->reduce($scope);
        $right = $this->right->reduce($scope);

        $left->addListener($right);
    }
}

