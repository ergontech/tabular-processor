<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChunkStepSpec extends ObjectBehavior
{
    private $rows;

    private $next;

    private $chunksize = 1;

    private $chunks = [
        ['one-a', 'one-b'],
        ['two-a', 'two-b'],
        ['three-a', 'three-b']
    ];

    private $columnHeaders = ['foo', 'bar'];

    function let(
        Rows $rows,
        chunknext $next)
    {
        $rows->getColumnHeaders()->willReturn($this->columnHeaders);
        $rows->getRows()->willReturn($this->chunks);
        $next->__invoke(Argument::type(Rows::class))
            ->will(function ($args) {
                return $args[0];
            });
        $this->beConstructedWith($this->chunksize);
        $this->rows = $rows;
        $this->next = $next;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(\ErgonTech\Tabular\ChunkStep::class);
    }

    function it_is_a_step()
    {
        $this->shouldImplement(Step::class);
    }

    function it_calls_next_the_right_number_of_times_on_invoke()
    {
        $this->next->__invoke(Argument::type(Rows::class))->shouldBeCalledTimes(3);
        $rows = $this->__invoke($this->rows, $this->next);
    }
}

class chunknext
{
    function __invoke($x) { return $x; }
}
