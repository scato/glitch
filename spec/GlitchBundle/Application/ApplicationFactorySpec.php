<?php

namespace spec\GlitchBundle\Application;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApplicationFactorySpec extends ObjectBehavior
{
    function it_creates_an_interpreter_application()
    {
        $this->createApplication()->shouldHaveType('GlitchBundle\Application\InterpreterApplication');
    }
}
