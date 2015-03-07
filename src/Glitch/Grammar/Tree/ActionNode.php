<?php

namespace Glitch\Grammar\Tree;

class ActionNode
{
    private $parameters;
    private $statements;

    public function __construct($parameters, $statements)
    {
        $this->parameters = $parameters;
        $this->statements = $statements;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getStatements()
    {
        return $this->statements;
    }
}
