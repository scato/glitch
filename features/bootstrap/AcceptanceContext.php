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

        $this->interpreter = new Interpreter(
            $this->filesystem->reveal(),
            new GlitchFile(),
            new CliFactory()
        );
    }

    /**
     * @Given I have a Hello, world! example
     */
    public function iHaveAHelloWorldExample()
    {
        $this->filesystem->read('example.g')->willReturn('main += args => { println ! "Hello, world!"; };');
    }

    /**
     * @When I run it
     */
    public function iRunIt()
    {
        $this->interpreter->run('example.g', $this->output->reveal());
    }

    /**
     * @Then I should see the expected output
     */
    public function iShouldSeeTheExpectedOutput()
    {
        $this->output->writeln('Hello, world!')->shouldHaveBeenCalled();
    }
}
