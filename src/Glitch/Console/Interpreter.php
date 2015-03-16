<?php

namespace Glitch\Console;

use League\Flysystem\FilesystemInterface;
use Glitch\Grammar\GlitchFile;
use Glitch\Runtime\StringValue;
use Symfony\Component\Console\Output\OutputInterface;

class Interpreter
{
    private $filesystem;
    private $grammar;
    private $activationObjectFactory;

    public function __construct(
        FilesystemInterface $filesystem,
        GlitchFile $grammar,
        CliFactory $activationObjectFactory
    ) {
        $this->filesystem = $filesystem;
        $this->grammar = $grammar;
        $this->activationObjectFactory = $activationObjectFactory;
    }

    public function run($filename, OutputInterface $output)
    {
        $contents = $this->filesystem->read($filename);
        $program = $this->grammar->parse($contents);
        $global = $this->activationObjectFactory->createActivationObject($output);

        $program->run($global);
        $global->get('main')->fire(new StringValue(''));
    }
}
