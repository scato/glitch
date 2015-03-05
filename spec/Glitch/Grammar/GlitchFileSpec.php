<?php

namespace spec\Glitch\Grammar;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GlitchFileSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Glitch\Grammar\GlitchFile');
    }
}
