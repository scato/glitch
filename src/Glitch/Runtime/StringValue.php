<?php

namespace Glitch\Runtime;

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
