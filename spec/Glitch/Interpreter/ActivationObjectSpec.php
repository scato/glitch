<?php

namespace spec\Glitch\Interpreter;

use Glitch\Interpreter\AssignmentException;
use Glitch\Interpreter\ReferenceException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActivationObjectSpec extends ObjectBehavior
{
    function it_fails_when_trying_to_retrieve_unknown_reference()
    {
        $this->shouldThrow(new ReferenceException('foo'))->duringGet('foo');
    }

    function it_returns_values_for_known_references()
    {
        $this->set('bar', 'foo');

        $this->get('bar')->shouldBe('foo');
    }

    function it_fails_when_trying_to_set_the_same_reference_twice()
    {
        $this->set('bar', 'foo');
        
        $this->shouldThrow(new AssignmentException('bar'))->duringSet('bar', 'bar');
    }
}
