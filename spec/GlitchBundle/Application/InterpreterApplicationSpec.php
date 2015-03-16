<?php

namespace spec\GlitchBundle\Application;

use Glitch\Console\Interpreter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

class InterpreterApplicationSpec extends ObjectBehavior
{
    function let(Interpreter $interpreter)
    {
        $this->beConstructedWith($interpreter);

        $this->setAutoExit(false);
    }

    function it_should_run_the_interpreter(Interpreter $interpreter)
    {
        $input = new StringInput('doc/example.g');
        $output = new NullOutput();
        $this->run($input, $output);

        $interpreter->run(\getcwd() . '/doc/example.g', $output)->shouldBeCalled();
    }
}
