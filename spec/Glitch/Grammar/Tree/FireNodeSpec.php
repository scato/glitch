<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\ActionInterface;
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

    function it_should_fire_an_action_when_invoked(ActivationObject $scope, ReferenceNode $left, StringNode $a, StringNode $b, ActionInterface $action)
    {
        $left->reduce($scope)->willReturn($action);
        $a->reduce($scope)->willReturn(new StringValue('a'));
        $b->reduce($scope)->willReturn(new StringValue('b'));

        $this->invoke($scope);

        $action->fire([new StringValue('a'), new StringValue('b')])->shouldBeCalled();
    }
}
