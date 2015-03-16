<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\EventValue;
use Glitch\Runtime\StringValue;
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

    function it_fires_an_event_when_invoked(ActivationObject $scope, ReferenceNode $left, ReferenceNode $right, EventValue $event, StringValue $value)
    {
        $left->reduce($scope)->willReturn($event);
        $right->reduce($scope)->willReturn($value);

        $this->invoke($scope);

        $event->fire($value)->shouldBeCalled();
    }
}
