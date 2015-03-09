<?php

namespace spec\Glitch\Grammar\Tree;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RemoveListenerNodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\RemoveListenerNode');
    }
}
