<?php

namespace Glitch\Runtime;

class ActivationObject
{
    private $parent;
    private $values = array();

    public function __construct($parent = null)
    {
        $this->parent = $parent;
    }

    public function get($key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }

        if ($this->parent !== null) {
            return $this->parent->get($key);
        }

        throw new ReferenceException($key);
    }

    public function set($key, $value)
    {
        if (isset($this->values[$key])) {
            throw new AssignmentException($key);
        }

        $this->values[$key] = $value;
    }
}
