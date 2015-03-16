<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RemoveListenerNodeSpec extends ObjectBehavior
{
    function let(ReferenceNode $left, StringNode $right)
    {
        $this->beConstructedWith($left, $right);
    }

    function it_is_a_statement()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\StatementNode');
    }
}
