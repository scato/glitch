<?php

namespace spec\Glitch\Runtime;

use Glitch\Runtime\EventValue;
use Glitch\Runtime\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventValueSpec extends ObjectBehavior
{
    function it_fires_listeners_that_were_added(EventValue $listener)
    {
        $value = new StringValue('test');

        $this->addListener($listener);
        $this->fire($value);

        $listener->fire($value)->shouldBeCalled();
    }

    function it_will_not_fire_listeners_that_were_removed(EventValue $listener)
    {
        $value = new StringValue('test');

        $this->addListener($listener);
        $this->removeListener($listener);
        $this->fire($value);

        $listener->fire($value)->shouldNotBeCalled();
    }
}
