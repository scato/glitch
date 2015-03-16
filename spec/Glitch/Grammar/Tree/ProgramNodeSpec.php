<?php

namespace spec\Glitch\Grammar\Tree;

use Glitch\Grammar\Tree\FireNode;
use Glitch\Runtime\ActivationObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProgramNodeSpec extends ObjectBehavior
{
    function let(FireNode $statement)
    {
        $this->beConstructedWith(array($statement));
    }

    function it_invokes_all_statements_when_run(ActivationObject $scope, FireNode $statement)
    {
        $this->run($scope);

        $statement->invoke($scope)->shouldBeCalled();
    }
}
