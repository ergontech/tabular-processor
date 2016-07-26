<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Processor;
use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use ErgonTech\Tabular\StepExecutionException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProcessorSpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType(Processor::class);
    }

    public function it_can_receive_new_steps(Step $step)
    {
        $this->addStep($step);
    }

    public function it_can_run_the_steps(Step $step)
    {
        $this->addStep($step);
        $step
            ->__invoke(
                Argument::type(Rows::class),
                Argument::type('callable'))
            ->shouldBeCalled();
        $this->run();
    }

    public function it_is_a_step(Rows $rows)
    {
        $this->__invoke($rows)->shouldReturnAnInstanceOf(Rows::class);
    }

    public function it_passes_back_the_result_of_the_steps(Rows $rows, Step $step)
    {
        $step
            ->__invoke(
                Argument::type(Rows::class),
                Argument::type('callable'))
            ->willReturn($rows)
            ->shouldBeCalled();
        $inputRows = [['asdf', 'asdf']];
        $rows->getRows()->willReturn($inputRows);

        $this->addStep($step);

        $resultRows = $this->run();
        $resultRows->shouldHaveType(Rows::class);
        $resultRows->getRows()->shouldBeLike($inputRows);
    }
}
