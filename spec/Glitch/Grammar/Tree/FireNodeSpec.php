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
    function let(ReferenceNode $left, StringNode $a, StringNode $b)
    {
        $this->beConstructedWith($left, [$a, $b]);
    }

    function it_is_a_statement()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\StatementNode');
    }

    function it_fires_an_event_when_invoked(ActivationObject $scope, ReferenceNode $left, StringNode $a, StringNode $b, EventValue $event)
    {
        $left->reduce($scope)->willReturn($event);
        $a->reduce($scope)->willReturn(new StringValue('a'));
        $b->reduce($scope)->willReturn(new StringValue('b'));

        $this->invoke($scope);

        $event->fire([new StringValue('a'), new StringValue('b')])->shouldBeCalled();
    }
}
