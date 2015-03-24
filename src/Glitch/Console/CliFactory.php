<?php

namespace Glitch\Console;

use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\EventValue;
use Symfony\Component\Console\Output\OutputInterface;

class CliFactory
{
    private function createMainEvent()
    {
        return new EventValue();
    }

    private function createPrintlnAction(OutputInterface $output)
    {
        return new CallbackAction(array($output, 'writeln'));
    }

    private function createIncludeAction(Interpreter $interpreter)
    {
        return new CallbackAction(array($interpreter, 'includeFile'));
    }

    public function createActivationObject(OutputInterface $output, Interpreter $interpreter)
    {
        $activationObject = new ActivationObject();

        $activationObject->set('main', $this->createMainEvent());
        $activationObject->set('println', $this->createPrintlnAction($output));
        $activationObject->set('include', $this->createIncludeAction($interpreter));

        return $activationObject;
    }
}
