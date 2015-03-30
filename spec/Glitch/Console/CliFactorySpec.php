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
    
    function it_creates_built_in_events(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $activationObject = $this->createActivationObject($output, $interpreter, $filesystem);

        $activationObject->get('main')->shouldHaveType('Glitch\Runtime\EventValue');
    }
    
    function it_creates_built_in_actions(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $activationObject = $this->createActivationObject($output, $interpreter, $filesystem);

        $activationObject->get('println')->shouldHaveType('Glitch\Runtime\ActionInterface');
        $activationObject->get('include')->shouldHaveType('Glitch\Runtime\ActionInterface');
        $activationObject->get('file_get_contents')->shouldHaveType('Glitch\Runtime\ActionInterface');
        $activationObject->get('microtime')->shouldHaveType('Glitch\Runtime\ActionInterface');
    }
    
    function it_creates_built_in_functions(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $activationObject = $this->createActivationObject($output, $interpreter, $filesystem);

        $activationObject->get('strpos')->shouldHaveType('Glitch\Runtime\FunctionInterface');
        $activationObject->get('substr')->shouldHaveType('Glitch\Runtime\FunctionInterface');
        $activationObject->get('strlen')->shouldHaveType('Glitch\Runtime\FunctionInterface');
        $activationObject->get('trim')->shouldHaveType('Glitch\Runtime\FunctionInterface');
        $activationObject->get('md5')->shouldHaveType('Glitch\Runtime\FunctionInterface');
    }
}
