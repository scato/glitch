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
        $backfire = null;

        if (end($values) instanceof ActionInterface) {
            $backfire = array_pop($values);
        }

        $strings = array_map(function (StringValue $value) {
            return $value->toString();
        }, $values);

        $result = call_user_func_array($this->callback, $strings);

        if ($backfire !== null) {
            $backfire->fire([new StringValue($result)]);
        }
    }
}
