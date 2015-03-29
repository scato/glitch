<?php

namespace spec\Glitch\Console;

use Glitch\Console\Interpreter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Output\OutputInterface;
use League\Flysystem\FilesystemInterface;

class CliFactorySpec extends ObjectBehavior
{
    function it_creates_an_activation_object(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $this->createActivationObject($output, $interpreter, $filesystem)
             ->shouldHaveType('Glitch\Runtime\ActivationObject');
    }
    
    function it_creates_a_main_event(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $this->createActivationObject($output, $interpreter, $filesystem)
             ->get('main')->shouldHaveType('Glitch\Runtime\EventValue');
    }
    
    function it_creates_a_println_action(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $this->createActivationObject($output, $interpreter, $filesystem)
             ->get('println')->shouldHaveType('Glitch\Runtime\ActionInterface');
    }
    
    function it_creates_an_include_action(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $this->createActivationObject($output, $interpreter, $filesystem)
             ->get('include')->shouldHaveType('Glitch\Runtime\ActionInterface');
    }
    
    function it_creates_a_file_get_contents_action(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $this->createActivationObject($output, $interpreter, $filesystem)
             ->get('file_get_contents')->shouldHaveType('Glitch\Runtime\ActionInterface');
    }
}
