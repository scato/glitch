<?php

namespace Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\EventValue;

class EventDefinitionNode implements StatementNode
{
    private $names;

    public function __construct(array $names)
    {
        $this->names = $names;
    }

    public function invoke(ActivationObject $scope)
    {
        foreach ($this->names as $name) {
            $scope->set($name, new EventValue());
        }
    }
}
