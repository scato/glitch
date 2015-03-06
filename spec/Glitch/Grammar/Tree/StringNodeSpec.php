<?php

namespace spec\Glitch\Grammar\Tree;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StringNodeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('""');
    }

    function it_has_a_value()
    {
        $this->getValue()->shouldBe('""');
    }
}
