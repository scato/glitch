<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Glitch\Grammar\GlitchFile;
use Glitch\Console\Interpreter;
use Glitch\Console\CliFactory;
use Prophecy\Prophet;

/**
 * Defines application features from the specific context.
 */
class AcceptanceContext implements Context, SnippetAcceptingContext
{
    private $prophet;
    private $interpreter;
    private $output;
    private $filesystem;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->prophet = new Prophet();

        $this->output = $this->prophet->prophesize();
        $this->output->willImplement('Symfony\Component\Console\Output\OutputInterface');

        $this->filesystem = $this->prophet->prophesize();
        $this->filesystem->willImplement('League\Flysystem\FilesystemInterface');

        foreach (['lib/stdlib.g', 'features/examples/languages.txt'] as $filename) {
            $this->filesystem->read($filename)->willReturn(
                file_get_contents($filename)
            );
        }

        $this->interpreter = new Interpreter(
            $this->filesystem->reveal(),
            new GlitchFile(),
            new CliFactory()
        );
    }

    /**
     * @Given I have a/an :name example
     */
    public function iHaveAnExample($name)
    {
        $this->filesystem->read('example.g')->willReturn(
            file_get_contents("features/examples/{$name}.g")
        );
    }

    /**
     * @When I run it
     */
    public function iRunIt()
    {
        $this->iRunItWith('');
    }

    /**
     * @When I run it with :args
     */
    public function iRunItWith($args)
    {
        $this->interpreter->runFile('example.g', $args, $this->output->reveal());
    }

    /**
     * @Then I should see :line
     */
    public function iShouldSee($line)
    {
        $this->output->writeln($line)->shouldHaveBeenCalled();
    }

    /**
     * @Then I should see the following:
     */
    public function iShouldSeeTheFollowing(PyStringNode $string)
    {
        foreach ($string->getStrings() as $line) {
            $this->output->writeln($line)->shouldHaveBeenCalled();
        }
    }
}
