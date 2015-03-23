<?php

namespace spec\Glitch\Runtime;

use Glitch\Runtime\EventValue;
use Glitch\Runtime\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventValueSpec extends ObjectBehavior
{
    function it_is_an_action()
    {
        $this->shouldHaveType('Glitch\Runtime\ActionInterface');
    }

    function it_is_a_value()
    {
        $this->shouldHaveType('Glitch\Runtime\ValueInterface');
    }

    function it_fires_listeners_that_were_added(EventValue $listener)
    {
        $values = [new StringValue('test')];

        $this->addListener($listener);
        $this->fire($values);

        $listener->fire($values)->shouldBeCalled();
    }

    function it_will_not_fire_listeners_that_were_removed(EventValue $listener)
    {
        $values = [new StringValue('test')];

        $this->addListener($listener);
        $this->removeListener($listener);
        $this->fire($values);

        $listener->fire($values)->shouldNotBeCalled();
    }

    function it_fires_listeners_that_were_not_removed(EventValue $listener, EventValue $anotherOne)
    {
        $values = [new StringValue('test')];

        $this->addListener($listener);
        $this->addListener($anotherOne);
        $this->removeListener($anotherOne);
        $this->fire($values);

        $listener->fire($values)->shouldBeCalled();
    }

    function it_fires_listeners_that_were_not_removed(EventValue $listener, EventValue $anotherOne)
    {
        $values = [new StringValue('test')];

        $this->addListener($listener);
        $this->addListener($anotherOne);
        $this->removeListener($anotherOne);
        $this->fire($values);

        $listener->fire($values)->shouldBeCalled();
    }
}
