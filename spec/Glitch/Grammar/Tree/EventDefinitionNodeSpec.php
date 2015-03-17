<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventDefinitionNodeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['a', 'b']);
    }

    function it_is_a_statement()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\StatementNode');
    }

    function it_defines_a_events_when_invoked(ActivationObject $scope)
    {
        $this->invoke($scope);

        $scope->set('a', Argument::type('Glitch\Runtime\EventValue'))->shouldBeCalled();
        $scope->set('b', Argument::type('Glitch\Runtime\EventValue'))->shouldBeCalled();
    }
}
