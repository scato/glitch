<?php

namespace spec\Glitch\Interpreter;

use Glitch\Interpreter\ActivationObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CliSpec extends ObjectBehavior
{
    function let(ActivationObject $global)
    {
        $this->beConstructedWith($global);
    }

    function it_should_add_main_event_to_global_scope(ActivationObject $global)
    {
        $global->set('main', null);

        $this->init();
    }
}
