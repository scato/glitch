<?php

namespace Glitch\Console;

use Glitch\Runtime\ActivationObject;
use Glitch\Runtime\EventValue;
use Symfony\Component\Console\Output\OutputInterface;
use League\Flysystem\FilesystemInterface;

class CliFactory
{
    public function createActivationObject(
        OutputInterface $output,
        Interpreter $interpreter,
        FilesystemInterface $filesystem
    ) {
        $activationObject = new ActivationObject();

        $activationObject->set('main', new EventValue());

        $activationObject->set('println', new CallbackAction(array($output, 'writeln')));
        $activationObject->set('include', new CallbackAction(array($interpreter, 'includeFile')));
        $activationObject->set('file_get_contents', new CallbackAction(array($filesystem, 'read')));
        $activationObject->set('microtime', new CallbackAction('microtime'));

        $activationObject->set('strpos', new CallbackFunction('strpos'));
        $activationObject->set('substr', new CallbackFunction('substr'));
        $activationObject->set('strlen', new CallbackFunction('strlen'));
        $activationObject->set('trim', new CallbackFunction('trim'));
        $activationObject->set('md5', new CallbackFunction('md5'));

        return $activationObject;
    }
}
