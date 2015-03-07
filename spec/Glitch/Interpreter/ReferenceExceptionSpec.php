<?php

namespace spec\Glitch\Interpreter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReferenceExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foo');
    }

    function it_reports_an_undefined_reference()
    {
        $this->getMessage()->shouldBe('foo is undefined');
    }
}
