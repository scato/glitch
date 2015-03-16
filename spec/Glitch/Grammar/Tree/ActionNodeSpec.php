<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\FireNode;
use Glitch\Interpreter\ActivationObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionNodeSpec extends ObjectBehavior
{
    function let(FireNode $statement)
    {
        $this->beConstructedWith(['x'], [$statement]);
    }

    function it_is_an_expression()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\ExpressionNode');
    }

    function it_reduces_to_an_action_value(ActivationObject $scope)
    {
        $this->reduce($scope)->shouldHaveType('Glitch\Interpreter\ActionValue');
    }
}
