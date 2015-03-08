<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;
use Glitch\Interpreter\ActivationObject;
use Glitch\Interpreter\EventValue;
use Glitch\Interpreter\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FireNodeSpec extends ObjectBehavior
{
    function let(ReferenceNode $left, StringNode $right)
    {
        $this->beConstructedWith($left, $right);
    }

    function it_is_a_statement()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\StatementNode');
    }

    function it_has_a_left_hand_side(ReferenceNode $left)
    {
        $this->getLeft()->shouldBe($left);
    }

    function it_has_a_right_hand_side(StringNode $right)
    {
        $this->getRight()->shouldBe($right);
    }

    function it_fires_an_event_when_invoked(ActivationObject $scope, ReferenceNode $left, ReferenceNode $right, EventValue $event, StringValue $value)
    {
        $left->reduce($scope)->willReturn($event);
        $right->reduce($scope)->willReturn($value);

        $this->invoke($scope);

        $event->fire($value)->shouldBeCalled();
    }
}
