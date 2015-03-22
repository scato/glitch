<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;

class EventListenerNode implements StatementNode
{
    protected $left;
    protected $operator;
    protected $right;

    public function __construct(ExpressionNode $left, $operator, ExpressionNode $right)
    {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }

    public function invoke(ActivationObject $scope)
    {
        $left = $this->left->reduce($scope);
        $right = $this->right->reduce($scope);

        switch ($this->operator) {
            case "+=":
                $left->addListener($right);
                break;
            case "-=":
                $left->removeListener($right);
                break;
            default:
                throw new LogicException("Unknown operator: {$this->operator}");
        }
    }
}

