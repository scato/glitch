<?php

namespace spec\Glitch\Console;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Output\OutputInterface;

class CliFactorySpec extends ObjectBehavior
{
    function it_creates_an_activation_object(OutputInterface $output)
    {
        $this->createActivationObject($output)->shouldHaveType('Glitch\Runtime\ActivationObject');
    }
    
    function it_creates_a_main_event(OutputInterface $output)
    {
        $this->createActivationObject($output)->get('main')->shouldHaveType('Glitch\Runtime\EventValue');
    }
    
    function it_creates_a_println_event(OutputInterface $output)
    {
        $this->createActivationObject($output)->get('println')->shouldHaveType('Glitch\Runtime\EventValue');
    }
}
