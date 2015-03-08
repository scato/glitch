<?php

namespace spec\Glitch\Interpreter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionValueSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Glitch\Interpreter\ActionValue');
    }
}
