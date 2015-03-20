<?php

namespace spec\Glitch\Runtime;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RuntimeException;

class StringValueSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Hello, world!');
    }

    function it_can_be_converted_to_a_string()
    {
        $this->toString()->shouldBe('Hello, world!');
    }

    function it_can_hold_a_non_boolean_value()
    {
        $this->shouldThrow(new RuntimeException("Not a boolean"))
            ->duringToBoolean();
    }

    function it_can_hold_a_boolean_true_value()
    {
        $this->beConstructedWith('true');
        $this->toBoolean()->shouldBe(true);
    }

    function it_can_hold_a_boolean_false_value()
    {
        $this->beConstructedWith('false');
        $this->toBoolean()->shouldBe(false);
    }
}
