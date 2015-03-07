<?php

namespace Glitch\Interpreter;

class StringValue
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function toString()
    {
        return $this->value;
    }
}
