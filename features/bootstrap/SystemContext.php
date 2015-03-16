<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Prophecy\Prophet;
use Symfony\Component\Process\Process;

/**
 * Defines application features from the specific context.
 */
class SystemContext implements Context, SnippetAcceptingContext
{
    private $filename;
    private $process;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function prepare(BeforeScenarioScope $scope)
    {
        $this->filename = tempnam('/tmp/', 'glitch-');
    }

    /**
     * @AfterScenario
     */
    public function cleanTmp(AfterScenarioScope $scope)
    {
        unlink($this->filename);
    }

    /**
     * @Given I have a Hello, world! example
     */
    public function iHaveAHelloWorldExample()
    {
        file_put_contents($this->filename, 'main += args => { println ! "Hello, world!"; };');
    }

    /**
     * @When I run it
     */
    public function iRunIt()
    {
        $this->process = new Process("bin/glitch {$this->filename}");
        $this->process->run();

        if (!$this->process->isSuccessful()) {
            throw new RuntimeException($this->process->getErrorOutput());
        }
    }

    /**
     * @Then I should see the expected output
     */
    public function iShouldSeeTheExpectedOutput()
    {
        assert(var_export($this->process->getOutput(), true) . ' === "Hello, world!\n"');
    }
}

