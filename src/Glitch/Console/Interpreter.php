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
    private $global;

    public function __construct(
        FilesystemInterface $filesystem,
        GlitchFile $grammar,
        CliFactory $activationObjectFactory
    ) {
        $this->filesystem = $filesystem;
        $this->grammar = $grammar;
        $this->activationObjectFactory = $activationObjectFactory;
    }

    public function runFile($filename, $args, OutputInterface $output)
    {
        $this->init($output, $this->filesystem);
        $this->includeFile($filename);
        $this->global->get('main')->fire([new StringValue($args)]);
    }

    public function init(OutputInterface $output, FilesystemInterface $filesystem)
    {
        $this->global = $this->activationObjectFactory->createActivationObject($output, $this, $filesystem);
    }

    public function includeFile($filename)
    {
        $contents = $this->filesystem->read($filename);
        $program = $this->grammar->parse($contents);
        $program->run($this->global);
    }
}
