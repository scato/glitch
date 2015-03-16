<?php

namespace Glitch\Console;

use Glitch\Runtime\ActionInterface;
use Glitch\Runtime\EventValue;
use Glitch\Runtime\ValueInterface;

class CallbackEvent implements ActionInterface, ValueInterface
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function fire(ValueInterface $value)
    {
        call_user_func($this->callback, $value->toString());
    }
}
