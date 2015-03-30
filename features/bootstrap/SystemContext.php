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
     * @Given I have a/an :name example
     */
    public function iHaveAnExample($name)
    {
        $this->filename = "features/examples/{$name}.g";
    }

    /**
     * @When I run it with :args
     */
    public function iRunItWith($args)
    {
        $this->process = new Process("bin/glitch {$this->filename} {$args}");
        $this->process->run();

        if (!$this->process->isSuccessful()) {
            throw new RuntimeException($this->process->getErrorOutput());
        }
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
     * @Then I should see :line
     */
    public function iShouldSee($line)
    {
        assert(var_export($this->process->getOutput(), true) . ' === ' . var_export($line . "\n", true));
    }

    /**
     * @Then I should see the following:
     */
    public function iShouldSeeTheFollowing(PyStringNode $string)
    {
        assert(var_export($this->process->getOutput(), true) . ' === ' . var_export($string->__toString() . "\n", true));
    }
}

