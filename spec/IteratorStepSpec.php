<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use ErgonTech\Tabular\StepExecutionException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IteratorStepSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(['adsf'], 'asdf');
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(\ErgonTech\Tabular\IteratorStep::class);
    }

    function it_is_a_step()
    {
        $this->shouldHaveType(Step::class);
    }

    function it_requires_items_and_columnName_on_init()
    {
        $this->beConstructedWith(['one', 'two', 'three'], 'extra');
    }

    function it_calls_the_next_step_N_times(NextStep $nextStep, Rows $rows)
    {
        $this->beConstructedWith(['a', 'b'], 'extra');

        $rows->getRows()->willReturn([
            ['foo', 'bar']
        ]);

        $rows->getColumnHeaders()->willReturn(['headerFoo', 'headerBar']);

        $nextStep->__invoke(Argument::type(Rows::class))
            ->shouldBeCalledTimes(2)
            ->will(function ($arg) {
                return $arg[0];
            });
        /** @var Rows $rows */
        $rows = $this->__invoke($rows, $nextStep);
        $rows->getRowsAssoc()->shouldReturn([
            [
                'headerFoo' => 'foo',
                'headerBar' => 'bar',
                'extra' => 'a'
            ],
            [
                'headerFoo' => 'foo',
                'headerBar' => 'bar',
                'extra' => 'b'
            ]
        ]);
    }

    function it_requires_all_column_headers_be_unique(Rows $rows)
    {
        $this->beConstructedWith(['a', 'b'], 'oops');

        $rows->getRows()->willReturn([
            ['foo', 'bar']
        ]);

        $rows->getColumnHeaders()->willReturn(['headerFoo', 'oops']);

        $this->shouldThrow(StepExecutionException::class)
            ->during('__invoke', [$rows, function ($r) { return $r; }]);
    }
}

class NextStep
{
    public function __invoke(Rows $stuff)
    {
        return $stuff;
    }
}
