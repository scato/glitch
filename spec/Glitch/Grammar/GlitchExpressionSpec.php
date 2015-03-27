<?php

namespace spec\Glitch\Grammar;

use Glitch\Grammar\Tree\ActionNode;
use Glitch\Grammar\Tree\AssignmentNode;
use Glitch\Grammar\Tree\BinaryNode;
use Glitch\Grammar\Tree\CallNode;
use Glitch\Grammar\Tree\EventDefinitionNode;
use Glitch\Grammar\Tree\EventListenerNode;
use Glitch\Grammar\Tree\FireNode;
use Glitch\Grammar\Tree\FunctionNode;
use Glitch\Grammar\Tree\ProgramNode;
use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\StringNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GlitchExpressionSpec extends ObjectBehavior
{
    function it_should_parse_a_string_literal()
    {
        $this->parse('"Hello, world!\\n"')->shouldBeLike(
            new StringNode('"Hello, world!\\n"')
        );
    }

    function it_should_parse_a_function_call_expression()
    {
        $this->parse('strtoupper("Hello, world!\\n")')->shouldBeLike(
            new CallNode(new ReferenceNode('strtoupper'), [new StringNode('"Hello, world!\\n"')])
        );
    }

    function it_should_parse_a_function_literal()
    {
        $this->parse('args -> args')->shouldBeLike(
            new FunctionNode(['args'], new ReferenceNode('args'))
        );
    }

    function it_should_parse_an_action_literal()
    {
        $this->parse('args => { STATEMENTS }')->shouldBeLike(
            new ActionNode(['args'], [])
        );
    }

    function it_should_parse_an_action_literal_with_zero_parameters()
    {
        $this->parse('() => { STATEMENTS }')->shouldBeLike(
            new ActionNode([], [])
        );
    }

    function it_should_parse_an_action_literal_with_multiple_parameters()
    {
        $this->parse('(argc, argv) => { STATEMENTS }')->shouldBeLike(
            new ActionNode(['argc', 'argv'], [])
        );
    }

    function it_should_parse_an_equality_expression()
    {
        $this->parse('a === b')->shouldBeLike(
            new BinaryNode('===', new ReferenceNode('a'), new ReferenceNode('b'))
        );
    }

    function it_should_parse_a_relational_expression()
    {
        $this->parse('a < b')->shouldBeLike(
            new BinaryNode('<', new ReferenceNode('a'), new ReferenceNode('b'))
        );
    }

    function it_should_parse_an_additive_expression()
    {
        $this->parse('a + b')->shouldBeLike(
            new BinaryNode('+', new ReferenceNode('a'), new ReferenceNode('b'))
        );
    }
}
