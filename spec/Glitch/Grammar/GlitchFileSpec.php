<?php

namespace spec\Glitch\Grammar;

use Glitch\Grammar\Tree\ProgramNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GlitchFileSpec extends ObjectBehavior
{
    function it_should_parse_an_empty_program()
    {
        $this->parse('')->shouldBeLike(new ProgramNode());
    }
}
