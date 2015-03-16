<?php

namespace GlitchBundle\Application;

use Glitch\Grammar\GlitchFile;
use Glitch\Console\CliFactory;
use Glitch\Console\Interpreter;
use GlitchBundle\Command\RunCommand;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;

class ApplicationFactory
{

    private function createInterpreter()
    {
        $adapter = new Adapter('/');
        $filesystem = new Filesystem($adapter);
        $grammar = new GlitchFile();
        $activationObjectFactory = new CliFactory();

        return new Interpreter($filesystem, $grammar, $activationObjectFactory);
    }

    public function createApplication()
    {
        return new InterpreterApplication($this->createInterpreter());
    }
}
