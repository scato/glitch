<?php

namespace Glitch\Interpreter;

class ActionValue extends EventValue
{
    private $parameters;
    private $statements;
    private $parentScope;

    public function __construct(array $parameters, array $statements, ActivationObject $parentScope)
    {
        $this->parameters = $parameters;
        $this->statements = $statements;
        $this->parentScope = $parentScope;
    }

    public function fire($value)
    {
        $scope = new ActivationObject($this->parentScope);
        $scope->set($this->parameters[0], $value);

        foreach ($this->statements as $statement) {
            $statement->invoke($scope);
        }
    }
}
