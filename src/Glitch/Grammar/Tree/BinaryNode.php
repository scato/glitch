<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\StringValue;
use RuntimeException;

class BinaryNode implements ExpressionNode
{
    private $operator;
    private $left;
    private $right;

    public function __construct($operator, ExpressionNode $left, ExpressionNode $right)
    {
        $this->operator = $operator;
        $this->left = $left;
        $this->right = $right;
    }

    private function apply($left, $right)
    {
        switch ($this->operator) {
            case '+':
                return $left->toString() + $right->toString();
            case '-':
                return $left->toString() - $right->toString();
            case '===':
                return $left->toString() === $right->toString();
            case '!==':
                return $left->toString() !== $right->toString();
            case '<':
                return strnatcmp($left->toString(), $right->toString()) < 0;
            case '>':
                return strnatcmp($left->toString(), $right->toString()) > 0;
            case '<=':
                return strnatcmp($left->toString(), $right->toString()) <= 0;
            case '>=':
                return strnatcmp($left->toString(), $right->toString()) >= 0;
            default:
                throw new RuntimeException("Operator '{$this->operator}' not supported");
        }
    }

    public function reduce(ActivationObject $scope)
    {
        $left = $this->left->reduce($scope);
        $right = $this->right->reduce($scope);

        $value = $this->apply($left, $right);

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        return new StringValue($value);
    }
}
