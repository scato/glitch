<?php

namespace spec\Glitch\Grammar;

use Glitch\Grammar\Tree\AddListenerNode;
use Glitch\Grammar\Tree\ActionNode;
use Glitch\Grammar\Tree\FireNode;
use Glitch\Grammar\Tree\ProgramNode;
use Glitch\Grammar\Tree\ReferenceNode;
use Glitch\Grammar\Tree\RemoveListenerNode;
use Glitch\Grammar\Tree\StringNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GlitchFileSpec extends ObjectBehavior
{
    function a_program_with($expression)
    {
        return new ProgramNode([
            new FireNode(new ReferenceNode('main'), $expression)
        ]);
    }

    function it_should_parse_an_empty_program()
    {
        $this->parse('')->shouldBeLike(new ProgramNode([]));
    }

    function it_should_parse_a_fire_statement()
    {
        $this->parse('main ! "";')->shouldBeLike(
            $this->a_program_with(new StringNode('""'))
        );
    }

    function it_should_parse_an_add_listener_statement()
    {
        $this->parse('main += args => { print ! args; };')->shouldBeLike(
            new ProgramNode([
                new AddListenerNode(new ReferenceNode('main'), new ActionNode(['args'], [
                    new FireNode(new ReferenceNode('print'), new ReferenceNode('args'))
                ]))
            ])
        );
    }

    function it_should_parse_a_remove_listener_statement()
    {
        $this->parse('main -= args => { print ! args; };')->shouldBeLike(
            new ProgramNode([
                new RemoveListenerNode(new ReferenceNode('main'), new ActionNode(['args'], [
                    new FireNode(new ReferenceNode('print'), new ReferenceNode('args'))
                ]))
            ])
        );
    }

    function it_should_parse_a_string_literal()
    {
        $this->parse('main ! "Hello, world!\\n";')->shouldBeLike(
            $this->a_program_with(new StringNode('"Hello, world!\\n"'))
        );
    }

    function it_should_parse_an_action_literal()
    {
        $this->parse('main ! args => { print ! args; };')->shouldBeLike(
            $this->a_program_with(new ActionNode(['args'], [
                new FireNode(new ReferenceNode('print'), new ReferenceNode('args'))
            ]))
        );
    }
}
