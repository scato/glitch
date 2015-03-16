<?php

namespace Glitch\Runtime;

class StringValue implements ValueInterface
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
