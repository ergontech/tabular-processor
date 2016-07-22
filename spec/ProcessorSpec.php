<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Processor;
use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use ErgonTech\Tabular\StepExecutionException;
use ErgonTech\Tabular\Steps;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class ProcessorSpec extends ObjectBehavior
{
    private $rows;

    /**
     * @var Steps
     */
    private $steps;

    public function let(Rows $rows, Steps $steps)
    {
        $this->rows = $rows;
        $this->steps = $steps;
        $this->beConstructedWith($this->rows, $this->steps);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Processor::class);
    }

    public function it_requires_a_dataprovider_and_steps(Rows $rows, Steps $steps)
    {
        $this->shouldThrow(\Exception::class)->during('__construct');
        $this->beConstructedWith();
    }

    public function it_does_not_process_any_steps_when_there_are_no_valid_steps()
    {
        $this->steps->getNext()
            ->willReturn(null)
            ->shouldBeCalledTimes(1);
        $this->run();
    }

    public function it_processes_steps_until_there_are_no_more_valid_steps(Step $step)
    {
        $steps = [$step];

        $this->steps->getNext()
            ->will(function () use (&$steps) {
                return count($steps)
                    ? array_pop($steps)
                    : null;
            });

        $step->__invoke($this->rows)->shouldBeCalled();

        $this->run();
    }

    public function it_stops_execution_when_a_step_throws_an_exception(Step $step1, Step $step2)
    {
        $step1->__invoke($this->rows)
            ->willThrow(StepExecutionException::class)
            ->shouldBeCalled();

        $step2->__invoke($this->rows)
            ->shouldNotBeCalled();

        $this->steps->getNext()
            ->willReturn($step1)
            ->shouldBeCalledTimes(1);

        $this->run();
    }
}
