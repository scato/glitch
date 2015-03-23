<?php

namespace spec\Glitch\Console;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use League\Flysystem\FilesystemInterface;
use Glitch\Grammar\GlitchFile;
use Glitch\Grammar\Tree\ProgramNode;
use Glitch\Console\CliFactory;
use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\EventValue;
use Glitch\Runtime\StringValue;
use Symfony\Component\Console\Output\OutputInterface;

class InterpreterSpec extends ObjectBehavior
{
    function let(FilesystemInterface $filesystem, GlitchFile $grammar, CliFactory $activationObjectFactory)
    {
        $this->beConstructedWith($filesystem, $grammar, $activationObjectFactory);
    }

    function it_should_run_a_file(
        FilesystemInterface $filesystem,
        GlitchFile $grammar,
        ProgramNode $programNode,
        CliFactory $activationObjectFactory,
        ActivationObject $activationObject,
        EventValue $main,
        OutputInterface $output
    ) {
        $filesystem->read('example.g')->willReturn('println ! "foo";');
        $grammar->parse('println ! "foo";')->willReturn($programNode);
        $activationObjectFactory->createActivationObject($output)->willReturn($activationObject);
        $activationObject->get('main')->willReturn($main);

        $this->run('example.g', 'test', $output);

        $programNode->run($activationObject)->shouldBeCalled();
        $main->fire([new StringValue("test")])->shouldBeCalled();
    }
}
