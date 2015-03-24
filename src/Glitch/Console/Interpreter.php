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
    private $openFiles = array();

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
        $this->init($output);
        $this->includeFile($filename);
        $this->global->get('main')->fire([new StringValue($args)]);
    }

    public function init(OutputInterface $output)
    {
        $this->global = $this->activationObjectFactory->createActivationObject($output, $this);
    }

    public function enterFile($filename)
    {
        if (!empty($this->openFiles) && !preg_match('/^\\//', $filename)) {
            $filename = dirname(end($this->openFiles)) . '/' . $filename;
        }

        array_push($this->openFiles, $filename);

        return $filename;
    }

    public function exitFile()
    {
        array_pop($this->openFiles);
    }

    public function includeFile($filename)
    {
        $filename = $this->enterFile($filename);

        $contents = $this->filesystem->read($filename);
        $program = $this->grammar->parse($contents);
        $program->run($this->global);

        $this->exitFile($filename);
    }
}
