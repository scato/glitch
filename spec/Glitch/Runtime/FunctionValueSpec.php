<?php

namespace spec\Glitch\Runtime;

use Glitch\Grammar\Tree\ExpressionNode;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FunctionValueSpec extends ObjectBehavior
{
    function let(ExpressionNode $expression, ActivationObject $parentScope)
    {
        $this->beConstructedWith(['x', 'y'], $expression, $parentScope);
    }
    
    function it_is_a_function()
    {
        $this->shouldHaveType('Glitch\Runtime\FunctionInterface');
    }

    function it_is_a_value()
    {
        $this->shouldHaveType('Glitch\Runtime\ValueInterface');
    }

    function it_should_reduce_its_expression_when_called(ExpressionNode $expression, ActivationObject $parentScope)
    {
        $values = [new StringValue('a'), new StringValue('b')];

        $this->call($values);

        $scope = new ActivationObject($parentScope->getWrappedObject());
        $scope->set('x', new StringValue('a'));
        $scope->set('y', new StringValue('b'));

        $expression->reduce($scope)->shouldBeCalled();
    }
}
