<?php

namespace Glitch\Runtime;

class ActionValue implements ActionInterface, ValueInterface
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

    public function fire(array $values)
    {
        $scope = new ActivationObject($this->parentScope);
        foreach ($this->parameters as $index => $parameter) {
            $scope->set($parameter, $values[$index]);
        }

        foreach ($this->statements as $statement) {
            $statement->invoke($scope);
        }
    }
}
