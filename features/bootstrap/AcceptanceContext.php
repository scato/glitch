<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Glitch\Runtime\StringValue;
use Prophecy\Prophet;

/**
 * Defines application features from the specific context.
 */
class AcceptanceContext implements Context, SnippetAcceptingContext
{
    private $prophet;
    private $println;
    private $example;

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

        $this->println = $this->prophet->prophesize();
        $this->println->willExtend('Glitch\Runtime\EventValue');
    }

    /**
     * @Given I have a Hello, world! example
     */
    public function iHaveAHelloWorldExample()
    {
        $this->example = 'main += args => { println ! "Hello, world!"; };';
    }

    /**
     * @When I run it
     */
    public function iRunIt()
    {
        $grammar = new \Glitch\Grammar\GlitchFile();
        $program = $grammar->parse($this->example);

        $global = new \Glitch\Runtime\ActivationObject();
        $global->set('main', new \Glitch\Runtime\EventValue());
        $global->set('println', $this->println->reveal());

        $program->run($global);
        $global->get('main')->fire(new StringValue(''));
    }

    /**
     * @Then I should see the expected output
     */
    public function iShouldSeeTheExpectedOutput()
    {
        $this->println->fire(new StringValue("Hello, world!"))->shouldHaveBeenCalled();
    }
}
