<?php

namespace spec\Glitch\Runtime;

use Glitch\Grammar\Tree\StatementNode;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionValueSpec extends ObjectBehavior
{
    function let(StatementNode $statement, ActivationObject $parentScope)
    {
        $this->beConstructedWith(['x', 'y'], [$statement], $parentScope);
    }
    
    function it_is_an_action()
    {
        $this->shouldHaveType('Glitch\Runtime\ActionInterface');
    }

    function it_is_a_value()
    {
        $this->shouldHaveType('Glitch\Runtime\ValueInterface');
    }

    function it_should_invoke_its_statements_when_fired(StatementNode $statement, ActivationObject $parentScope)
    {
        $values = [new StringValue('a'), new StringValue('b')];

        $this->fire($values);

        $scope = new ActivationObject($parentScope->getWrappedObject());
        $scope->set('x', new StringValue('a'));
        $scope->set('y', new StringValue('b'));

        $statement->invoke($scope)->shouldBeCalled();
    }
}
