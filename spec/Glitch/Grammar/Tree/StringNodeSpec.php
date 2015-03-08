<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Interpreter\ActivationObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StringNodeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('"test"');
    }

    function it_is_an_expression()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\ExpressionNode');
    }

    function it_has_a_value()
    {
        $this->getValue()->shouldBe('"test"');
    }

    function it_reduces_to_a_string_value(ActivationObject $scope)
    {
        $this->reduce($scope)->shouldHaveType('Glitch\Interpreter\StringValue');
        $this->reduce($scope)->toString()->shouldBe('test');
    }
}
