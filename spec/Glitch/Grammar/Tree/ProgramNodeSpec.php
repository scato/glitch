<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\FireNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProgramNodeSpec extends ObjectBehavior
{
    function let(FireNode $statement)
    {
        $this->beConstructedWith(array($statement));
    }

    function it_has_statements(FireNode $statement)
    {
        $this->getStatements()->shouldBeLike(array($statement));
    }
}