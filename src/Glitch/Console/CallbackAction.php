<?php

namespace Glitch\Console;

use Glitch\Runtime\ActionInterface;
use Glitch\Runtime\EventValue;
use Glitch\Runtime\StringValue;
use Glitch\Runtime\ValueInterface;

class CallbackAction implements ActionInterface, ValueInterface
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function fire(array $values)
    {
        $strings = array_map(function (StringValue $value) {
            return $value->toString();
        }, $values);

        call_user_func_array($this->callback, $strings);
    }
}
