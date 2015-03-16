<?php

namespace spec\Glitch\Runtime;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
}
