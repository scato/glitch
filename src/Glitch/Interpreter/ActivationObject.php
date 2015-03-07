<?php

namespace Glitch\Interpreter;

class ActivationObject
{
    private $values = array();

    public function get($key)
    {
        if (!isset($this->values[$key])) {
            throw new ReferenceException($key);
        }

        return $this->values[$key];
    }

    public function set($key, $value)
    {
        if (isset($this->values[$key])) {
            throw new AssignmentException($key);
        }

        $this->values[$key] = $value;
    }
}
