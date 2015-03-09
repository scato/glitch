<?php

namespace spec\Glitch\Interpreter;

use Glitch\Grammar\Tree\StatementNode;
use Glitch\Interpreter\ActivationObject;
use Glitch\Interpreter\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionValueSpec extends ObjectBehavior
{
    function let(StatementNode $statement, ActivationObject $parentScope)
    {
        $this->beConstructedWith(['x'], [$statement], $parentScope);
    }
    
    function it_is_an_event()
    {
        $this->shouldHaveType('Glitch\Interpreter\EventValue');
    }

    function it_should_invoke_its_statements_when_fired(StatementNode $statement, ActivationObject $parentScope, StringValue $value)
    {
        $this->fire($value);

        $scope = new ActivationObject($parentScope->getWrappedObject());
        $scope->set('x', $value->getWrappedObject());

        $statement->invoke($scope)->shouldBeCalled();
    }
}
