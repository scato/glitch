<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Interpreter\ActivationObject;
use Glitch\Interpreter\EventValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AddListenerNodeSpec extends ObjectBehavior
{
    function let(ReferenceNode $left, ReferenceNode $right)
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

    function it_has_a_right_hand_side(ReferenceNode $right)
    {
        $this->getRight()->shouldBe($right);
    }

    function it_adds_a_listener_when_invoked(ActivationObject $scope, ReferenceNode $left, ReferenceNode $right, EventValue $event, EventValue $listener)
    {
        $left->reduce($scope)->willReturn($event);
        $right->reduce($scope)->willReturn($listener);

        $this->invoke($scope);

        $event->addListener($listener)->shouldBeCalled();
    }
}
