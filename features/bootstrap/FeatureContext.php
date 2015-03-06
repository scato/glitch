<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
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

    private $example;

    /**
     * @Given I have a Hello, world! example
     */
    public function iHaveAHelloWorldExample()
    {
        $this->example = 'main += args => { println ! "Hello, world!"; };';
    }

    /**
     * @When I run the interpreter
     */
    public function iRunTheInterpreter()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see the expected output
     */
    public function iShouldSeeTheExpectedOutput()
    {
        throw new PendingException();
    }
}
