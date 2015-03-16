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
}
