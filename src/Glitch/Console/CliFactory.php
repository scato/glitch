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

    public function createActivationObject(OutputInterface $output)
    {
        $activationObject = new ActivationObject();

        $activationObject->set('main', $this->createMainEvent());
        $activationObject->set('println', $this->createPrintlnAction($output));

        return $activationObject;
    }
}
