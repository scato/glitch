<?php

namespace Glitch\Runtime;

use Glitch\Grammar\Tree\ExpressionNode;

class FunctionValue implements FunctionInterface
{
    private $parameters;
    private $expression;
    private $parentScope;

    public function __construct(array $parameters, ExpressionNode $expression, ActivationObject $parentScope)
    {
        $this->parameters = $parameters;
        $this->expression = $expression;
        $this->parentScope = $parentScope;
    }

    public function call(array $values)
    {
        $scope = new ActivationObject($this->parentScope);
        foreach ($this->parameters as $index => $parameter) {
            $scope->set($parameter, $values[$index]);
        }

        return $this->expression->reduce($scope);
    }
}
