<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ExpressionNode;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TernaryNodeSpec extends ObjectBehavior
{
    function let(ExpressionNode $first, ExpressionNode $second, ExpressionNode $third)
    {
        $this->beConstructedWith($first, $second, $third);
    }

    function it_is_an_expression()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\ExpressionNode');
    }

    function it_reduces_to_the_second_operand_if_the_first_operand_reduces_to_true(
        ExpressionNode $first, ExpressionNode $second, ActivationObject $scope
    ) {
        $value = new StringValue('test');
        $first->reduce($scope)->willReturn(new StringValue('true'));
        $second->reduce($scope)->willReturn($value);

        $this->reduce($scope)->shouldBe($value);
    }

    function it_reduces_to_the_third_operand_if_the_first_operand_reduces_to_false(
        ExpressionNode $first, ExpressionNode $third, ActivationObject $scope
    ) {
        $value = new StringValue('test');
        $first->reduce($scope)->willReturn(new StringValue('false'));
        $third->reduce($scope)->willReturn($value);

        $this->reduce($scope)->shouldBe($value);
    }
}
