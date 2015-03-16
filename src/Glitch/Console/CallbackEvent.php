<?php

namespace Glitch\Console;

use Glitch\Runtime\EventValue;

class CallbackEvent extends EventValue
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function fire($value)
    {
        call_user_func($this->callback, $value->toString());
    }
}
