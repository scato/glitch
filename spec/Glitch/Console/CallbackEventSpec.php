<?php

namespace spec\Glitch\Console;

use Glitch\Runtime\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Output\OutputInterface;

class CallbackEventSpec extends ObjectBehavior
{
    function let(OutputInterface $output)
    {
        $this->beConstructedWith(array($output, 'writeln'));
    }

    function it_is_an_event()
    {
        $this->shouldHaveType('Glitch\Runtime\EventValue');
    }

    function it_calls_the_callback_with_the_string_value(OutputInterface $output)
    {
        $this->fire(new StringValue('test'));

        $output->writeln('test')->shouldBeCalled();
    }
}
