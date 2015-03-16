<?php

namespace GlitchBundle\Application;

use Glitch\Console\Interpreter;
use GlitchBundle\Command\RunCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class InterpreterApplication extends Application
{
    public function __construct(Interpreter $interpreter)
    {
        parent::__construct();

        $this->add(new RunCommand($interpreter));
    }

    protected function getCommandName(InputInterface $input)
    {
        return 'run';
    }

    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->setArguments();

        return $definition;
    }
}
