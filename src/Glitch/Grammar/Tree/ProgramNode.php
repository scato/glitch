<?php

namespace Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;

class ProgramNode
{
    private $statements;

    public function __construct(array $statements)
    {
        $this->statements = $statements;
    }

    public function getStatements()
    {
        return $this->statements;
    }

    public function run(ActivationObject $scope)
    {
        foreach ($this->statements as $statement) {
            $statement->invoke($scope);
        }
    }
}
