<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ExpressionNode;
use Glitch\Runtime\ActivationObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FunctionNodeSpec extends ObjectBehavior
{
    function let(ExpressionNode $expression)
    {
        $this->beConstructedWith(['x', 'y'], $expression);
    }

    function it_is_an_expression()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\ExpressionNode');
    }

    function it_reduces_to_a_function_value(ActivationObject $scope)
    {
        $this->reduce($scope)->shouldHaveType('Glitch\Runtime\FunctionValue');
    }
}
