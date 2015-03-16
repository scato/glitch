<?php

namespace spec\Glitch\Runtime;

use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\AssignmentException;
use Glitch\Runtime\ReferenceException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActivationObjectSpec extends ObjectBehavior
{
    function let()
    {
        $parent = new ActivationObject();

        $this->beConstructedWith($parent);

        $parent->set('baz', 'foz');
    }

    function it_fails_when_trying_to_retrieve_unknown_reference()
    {
        $this->shouldThrow(new ReferenceException('foo'))->duringGet('foo');
    }

    function it_returns_values_for_known_references()
    {
        $this->set('bar', 'foo');

        $this->get('bar')->shouldBe('foo');
    }

    function it_fails_when_trying_to_set_same_reference_twice()
    {
        $this->set('bar', 'foo');
        
        $this->shouldThrow(new AssignmentException('bar'))->duringSet('bar', 'bar');
    }

    function it_inherits_from_a_parent_scope()
    {
        $this->get('baz')->shouldBe('foz');
    }

    function it_overrules_its_parent_scope()
    {
        $this->set('baz', 'foo');

        $this->get('baz')->shouldBe('foo');
    }
}
