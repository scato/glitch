<?php

namespace Glitch\Runtime;

use Exception;

class AssignmentException extends Exception
{
    public function __construct($key)
    {
        parent::__construct("$key is already defined");
    }
}
