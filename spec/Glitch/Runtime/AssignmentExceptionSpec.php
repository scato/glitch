<?php

namespace spec\Glitch\Runtime;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssignmentExceptionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('foo');
    }

    function it_reports_a_reference_that_is_already_defined()
    {
        $this->getMessage()->shouldBe('foo is already defined');
    }
}
