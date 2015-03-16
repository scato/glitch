<?php

namespace Glitch\Runtime;

use Exception;

class ReferenceException extends Exception
{

    public function __construct($key)
    {
        parent::__construct("$key is undefined");
    }
}
