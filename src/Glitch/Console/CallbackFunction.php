<?php

namespace Glitch\Console;

use Glitch\Runtime\StringValue;
use Glitch\Runtime\FunctionInterface;
use Glitch\Runtime\ValueInterface;

class CallbackFunction implements FunctionInterface, ValueInterface
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function call(array $values)
    {
        $strings = array_map(function (StringValue $value) {
            return $value->toString();
        }, $values);

        return new StringValue(call_user_func_array($this->callback, $strings));
    }
}
