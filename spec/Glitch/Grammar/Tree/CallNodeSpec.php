<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\FunctionInterface;
use Glitch\Runtime\StringValue;
use Glitch\Runtime\ValueInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallNodeSpec extends ObjectBehavior
{
    function let(ReferenceNode $left, StringNode $a, StringNode $b)
    {
        $this->beConstructedWith($left, [$a, $b]);
    }

    function it_is_an_expression()
    {
        $this->shouldHaveType('Glitch\Grammar\Tree\ExpressionNode');
    }

    function it_should_reduce_to_the_return_value_of_the_function_it_calls(ActivationObject $scope, ReferenceNode $left, StringNode $a, StringNode $b, FunctionInterface $function, ValueInterface $value)
    {
        $left->reduce($scope)->willReturn($function);
        $a->reduce($scope)->willReturn(new StringValue('a'));
        $b->reduce($scope)->willReturn(new StringValue('b'));
        $function->call([new StringValue('a'), new StringValue('b')])->willReturn($value);

        $this->reduce($scope)->shouldBe($value);
    }
}
