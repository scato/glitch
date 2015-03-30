<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ExpressionNode;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BinaryNodeSpec extends ObjectBehavior
{
    function it_is_an_expression(ExpressionNode $left, ExpressionNode $right)
    {
        $this->beConstructedWith('+', $left, $right);

        $this->shouldHaveType('Glitch\Grammar\Tree\ExpressionNode');
    }

    function it_should_add_two_numbers(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('+', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('1'));
        $right->reduce($scope)->willReturn(new StringValue('2'));

        $this->reduce($scope)->shouldBeLike(new StringValue('3'));
    }

    function it_should_subtract_two_numbers(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('-', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('1'));
        $right->reduce($scope)->willReturn(new StringValue('2'));

        $this->reduce($scope)->shouldBeLike(new StringValue('-1'));
    }

    function it_should_see_that_two_strings_are_equal(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('===', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('foo'));
        $right->reduce($scope)->willReturn(new StringValue('foo'));

        $this->reduce($scope)->shouldBeLike(new StringValue('true'));
    }

    function it_should_see_that_two_strings_are_not_equal(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('===', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('foo'));
        $right->reduce($scope)->willReturn(new StringValue('bar'));

        $this->reduce($scope)->shouldBeLike(new StringValue('false'));
    }

    function it_should_see_that_two_strings_are_not_unequal(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('!==', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('foo'));
        $right->reduce($scope)->willReturn(new StringValue('foo'));

        $this->reduce($scope)->shouldBeLike(new StringValue('false'));
    }

    function it_should_see_that_two_strings_are_unequal(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('!==', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('foo'));
        $right->reduce($scope)->willReturn(new StringValue('bar'));

        $this->reduce($scope)->shouldBeLike(new StringValue('true'));
    }

    function it_should_see_that_one_string_comes_before_another(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('<', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('bar'));
        $right->reduce($scope)->willReturn(new StringValue('foo'));

        $this->reduce($scope)->shouldBeLike(new StringValue('true'));
    }

    function it_should_see_that_one_string_does_not_come_before_another(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('<', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('foo'));
        $right->reduce($scope)->willReturn(new StringValue('bar'));

        $this->reduce($scope)->shouldBeLike(new StringValue('false'));
    }

    function it_should_see_that_one_number_comes_before_another(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('<', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('9'));
        $right->reduce($scope)->willReturn(new StringValue('10'));

        $this->reduce($scope)->shouldBeLike(new StringValue('true'));
    }

    function it_should_see_that_one_string_comes_after_another(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('>', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('foo'));
        $right->reduce($scope)->willReturn(new StringValue('bar'));

        $this->reduce($scope)->shouldBeLike(new StringValue('true'));
    }

    function it_should_see_that_one_string_does_not_come_after_another(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('>', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('bar'));
        $right->reduce($scope)->willReturn(new StringValue('foo'));

        $this->reduce($scope)->shouldBeLike(new StringValue('false'));
    }

    function it_should_see_that_two_strings_are_the_same(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('<=', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('foo'));
        $right->reduce($scope)->willReturn(new StringValue('foo'));

        $this->reduce($scope)->shouldBeLike(new StringValue('true'));
    }

    function it_should_see_that_two_strings_are_the_same_again(ExpressionNode $left, ExpressionNode $right, ActivationObject $scope)
    {
        $this->beConstructedWith('>=', $left, $right);
        $left->reduce($scope)->willReturn(new StringValue('foo'));
        $right->reduce($scope)->willReturn(new StringValue('foo'));

        $this->reduce($scope)->shouldBeLike(new StringValue('true'));
    }
}
