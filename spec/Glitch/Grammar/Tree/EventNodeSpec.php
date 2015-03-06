<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\FireNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventNodeSpec extends ObjectBehavior
{
    function let(FireNode $statement)
    {
        $this->beConstructedWith(['x'], [$statement]);
    }

    function it_has_parameters()
    {
        $this->getParameters()->shouldBeLike(['x']);
    }

    function it_has_statements(FireNode $statement)
    {
        $this->getStatements()->shouldBeLike([$statement]);
    }
}
