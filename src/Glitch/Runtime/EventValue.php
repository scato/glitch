<?php

namespace Glitch\Runtime;

class EventValue implements ActionInterface, ValueInterface
{
    private $listeners = array();

    public function addListener(ActionInterface $listener)
    {
        $this->listeners[] = $listener;
    }

    public function removeListener(ActionInterface $listener)
    {
        $listeners = array();

        foreach ($this->listeners as $listener) {
            if ($listener !== $listener) {
                $listeners[] = $listener;
            }
        }

        $this->listeners = $listeners;
    }

    public function fire(ValueInterface $value)
    {
        foreach ($this->listeners as $listener) {
            $listener->fire($value);
        }
    }
}
