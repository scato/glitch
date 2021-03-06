<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReferenceNodeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('main');
    }

    function it_is_an_expression()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\ExpressionNode');
    }

    function it_reduces_to_the_value_that_was_assigned_to_it(ActivationObject $scope, StringValue $value)
    {
        $scope->get('main')->willReturn($value);

        $this->reduce($scope)->shouldBe($value);
    }
}
