<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ExpressionNode;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\ValueInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssignmentNodeSpec extends ObjectBehavior
{
    function let(ExpressionNode $expression)
    {
        $this->beConstructedWith('x', $expression);
    }

    function it_should_set_the_value_in_the_current_scope(ActivationObject $scope, ExpressionNode $expression, ValueInterface $value)
    {
        $expression->reduce($scope)->willReturn($value);

        $this->invoke($scope);

        $scope->set('x', $value)->shouldBeCalled();
    }
}
