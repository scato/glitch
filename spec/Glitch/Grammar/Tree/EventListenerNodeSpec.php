<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\EventValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventListenerNodeSpec extends ObjectBehavior
{
    function it_is_a_statement(ReferenceNode $left, ReferenceNode $right)
    {
        $this->beConstructedWith($left, "", $right);

        $this->shouldHaveType('Glitch\Grammar\Tree\StatementNode');
    }

    function it_adds_a_listener_when_invoked(ActivationObject $scope, ReferenceNode $left, ReferenceNode $right, EventValue $event, EventValue $listener)
    {
        $this->beConstructedWith($left, "+=", $right);

        $left->reduce($scope)->willReturn($event);
        $right->reduce($scope)->willReturn($listener);

        $this->invoke($scope);

        $event->addListener($listener)->shouldBeCalled();
    }

    function it_removes_a_listener_when_invoked(ActivationObject $scope, ReferenceNode $left, ReferenceNode $right, EventValue $event, EventValue $listener)
    {
        $this->beConstructedWith($left, "-=", $right);

        $left->reduce($scope)->willReturn($event);
        $right->reduce($scope)->willReturn($listener);

        $this->invoke($scope);

        $event->removeListener($listener)->shouldBeCalled();
    }
}
