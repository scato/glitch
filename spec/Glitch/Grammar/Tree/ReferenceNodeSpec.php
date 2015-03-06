<?php

namespace spec\Glitch\Grammar\Tree;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReferenceNodeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('main');
    }

    function it_has_a_value()
    {
        $this->getValue()->shouldBe('main');
    }
}
