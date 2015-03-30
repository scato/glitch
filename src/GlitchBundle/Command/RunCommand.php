<?php

namespace GlitchBundle\Command;

use Glitch\Console\Interpreter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    private $interpreter;

    public function __construct(Interpreter $interpreter)
    {
        parent::__construct('run');

        $this->interpreter = $interpreter;
    }

    public function configure()
    {
        $this->addArgument('filename', InputArgument::REQUIRED, 'The program to run');
        $this->addArgument('args', InputArgument::OPTIONAL, 'The argument string to pass to the program', '');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $contents = $this->interpreter->runFile($filename, $input->getArgument('args'), $output);
    }
}
