<?php

namespace Glitch\Grammar\Tree;

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
}
