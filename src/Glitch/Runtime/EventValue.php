<?php

namespace Glitch\Runtime;

class EventValue
{
    private $listeners = array();

    public function addListener(EventValue $listener)
    {
        $this->listeners[] = $listener;
    }

    public function removeListener(EventValue $listener)
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
