<?php

namespace Glitch\Interpreter;

use Exception;

class AssignmentException extends Exception
{
    public function __construct($key)
    {
        parent::__construct("$key is already defined");
    }
}
