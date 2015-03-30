<?php

namespace Glitch\Runtime;

use RuntimeException;

class StringValue implements ValueInterface
{
    private $value;

    public function __construct($value)
    {
        if ($value === true) {
            $this->value = 'true';
        } elseif ($value === false) {
            $this->value = 'false';
        } else {
            $this->value = $value;
        }
    }

    public function toString()
    {
        return $this->value;
    }

    public function toBoolean()
    {
        if ($this->value === 'true') {
            return true;
        } elseif ($this->value === 'false') {
            return false;
        } else {
            throw new RuntimeException('Not a boolean');
        }
    }
}
