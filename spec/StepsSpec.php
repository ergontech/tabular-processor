<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Step;
use ErgonTech\Tabular\Steps;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StepsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Steps::class);
    }

    public function it_can_have_steps_added(Step $step1)
    {
        $this->add($step1);
    }

    public function it_returns_steps_in_order(Step $step1, Step $step2)
    {
        $this->add($step1);
        $this->add($step2);

        $this->getNext()->shouldEqual($step1);
        $this->getNext()->shouldEqual($step2);
    }
}
