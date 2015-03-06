<?php

namespace spec\Glitch\Interpreter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EnvironmentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Glitch\Interpreter\Environment');
    }
}
