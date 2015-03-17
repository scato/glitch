<?php

namespace spec\Glitch\Grammar;

use Glitch\Grammar\Tree\ActionNode;
use Glitch\Grammar\Tree\AddListenerNode;
use Glitch\Grammar\Tree\AssignmentNode;
use Glitch\Grammar\Tree\EventDefinitionNode;
use Glitch\Grammar\Tree\FireNode;
use Glitch\Grammar\Tree\ProgramNode;
use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\RemoveListenerNode;
use Glitch\Grammar\Tree\StringNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GlitchFileSpec extends ObjectBehavior
{
    function a_program_with(array $expressions)
    {
        return new ProgramNode([
            new FireNode(new ReferenceNode('main'), $expressions)
        ]);
    }

    function it_should_parse_an_empty_program()
    {
        $this->parse('')->shouldBeLike(new ProgramNode([]));
    }

    function it_should_parse_an_event_definition()
    {
        $this->parse('* a, b;')->shouldBeLike(
            new ProgramNode([
                new EventDefinitionNode(['a', 'b'])
            ])
        );
    }

    function it_should_parse_an_assignment_statement()
    {
        $this->parse('args := "";')->shouldBeLike(
            new ProgramNode([
                new AssignmentNode(new ReferenceNode('args'), new StringNode('""'))
            ])
        );
    }

    function it_should_parse_a_fire_statement()
    {
        $this->parse('main ! "";')->shouldBeLike(
            $this->a_program_with([new StringNode('""')])
        );
    }

    function it_should_parse_a_fire_statement_with_multiple_arguments()
    {
        $this->parse('main ! ("a", "b");')->shouldBeLike(
            $this->a_program_with([new StringNode('"a"'), new StringNode('"b"')])
        );
    }

    function it_should_parse_an_add_listener_statement()
    {
        $this->parse('main += args => { print ! args; };')->shouldBeLike(
            new ProgramNode([
                new AddListenerNode(new ReferenceNode('main'), new ActionNode(['args'], [
                    new FireNode(new ReferenceNode('print'), [new ReferenceNode('args')])
                ]))
            ])
        );
    }

    function it_should_parse_a_remove_listener_statement()
    {
        $this->parse('main -= args => { print ! args; };')->shouldBeLike(
            new ProgramNode([
                new RemoveListenerNode(new ReferenceNode('main'), new ActionNode(['args'], [
                    new FireNode(new ReferenceNode('print'), [new ReferenceNode('args')])
                ]))
            ])
        );
    }

    function it_should_parse_a_string_literal()
    {
        $this->parse('main ! "Hello, world!\\n";')->shouldBeLike(
            $this->a_program_with([new StringNode('"Hello, world!\\n"')])
        );
    }

    function it_should_parse_an_action_literal()
    {
        $this->parse('main ! args => { print ! args; };')->shouldBeLike(
            $this->a_program_with([new ActionNode(['args'], [
                new FireNode(new ReferenceNode('print'), [new ReferenceNode('args')])
            ])])
        );
    }
}
