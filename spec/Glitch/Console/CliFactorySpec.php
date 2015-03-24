<?php

namespace spec\Glitch\Console;

use Glitch\Console\Interpreter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Output\OutputInterface;

class CliFactorySpec extends ObjectBehavior
{
    function it_creates_an_activation_object(OutputInterface $output, Interpreter $interpreter)
    {
        $this->createActivationObject($output, $interpreter)->shouldHaveType('Glitch\Runtime\ActivationObject');
    }
    
    function it_creates_a_main_event(OutputInterface $output, Interpreter $interpreter)
    {
        $this->createActivationObject($output, $interpreter)->get('main')->shouldHaveType('Glitch\Runtime\EventValue');
    }
    
    function it_creates_a_println_action(OutputInterface $output, Interpreter $interpreter)
    {
        $this->createActivationObject($output, $interpreter)->get('println')->shouldHaveType('Glitch\Runtime\ActionInterface');
    }
    
    function it_creates_an_include_action(OutputInterface $output, Interpreter $interpreter)
    {
        $this->createActivationObject($output, $interpreter)->get('include')->shouldHaveType('Glitch\Runtime\ActionInterface');
    }
}
