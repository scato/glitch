<?php

namespace Glitch\Interpreter;

class EventValue
{
    private $listeners = array();

    public function add(EventValue $listener)
    {
        $this->listeners[] = $listener;
    }

    public function remove(EventValue $listener)
    {
        $listeners = array();

        foreach ($this->listeners as $listener) {
            if ($listener !== $listener) {
                $listeners[] = $listener;
            }
        }

        $this->listeners = $listeners;
    }

    public function fire($value)
    {
        foreach ($this->listeners as $listener) {
            $listener->fire($value);
        }
    }
}
