<?php

namespace spec\Glitch\Console;

use Glitch\Runtime\StringValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Helper\FormatterHelper;

class CallbackFunctionSpec extends ObjectBehavior
{
    function let(FormatterHelper $helper)
    {
        $this->beConstructedWith(array($helper, 'formatSection'));
    }

    function it_is_a_function()
    {
        $this->shouldHaveType('Glitch\Runtime\FunctionInterface');
    }

    function it_is_a_value()
    {
        $this->shouldHaveType('Glitch\Runtime\ValueInterface');
    }

    function it_calls_the_callback_with_the_string_value(FormatterHelper $helper)
    {
        $helper->formatSection('debug', 'test')->willReturn('[debug] test');

        $this->call([new StringValue('debug'), new StringValue('test')])->shouldBeLike(new StringValue('[debug] test'));

        $helper->formatSection('debug', 'test')->shouldBeCalled();
    }
}
