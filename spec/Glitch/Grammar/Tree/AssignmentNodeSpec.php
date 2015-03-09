<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssignmentNodeSpec extends ObjectBehavior
{
    function let(ReferenceNode $left, StringNode $right)
    {
        $this->beConstructedWith($left, $right);
    }

    function it_has_a_left_hand_side(ReferenceNode $left)
    {
        $this->getLeft()->shouldBe($left);
    }

    function it_has_a_right_hand_side(StringNode $right)
    {
        $this->getRight()->shouldBe($right);
    }
}
