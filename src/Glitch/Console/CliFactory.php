<?php

namespace Glitch\Console;

use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\EventValue;
use Symfony\Component\Console\Output\OutputInterface;
use League\Flysystem\FilesystemInterface;

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

    private function createFileGetContentsAction(FilesystemInterface $filesystem)
    {
        return new CallbackAction(array($filesystem, 'read'));
    }

    public function createActivationObject(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $activationObject = new ActivationObject();

        $activationObject->set('main', $this->createMainEvent());
        $activationObject->set('println', $this->createPrintlnAction($output));
        $activationObject->set('include', $this->createIncludeAction($interpreter));
        $activationObject->set('file_get_contents', $this->createFileGetContentsAction($filesystem));

        return $activationObject;
    }
}
