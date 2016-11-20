<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MergeStepSpec extends ObjectBehavior
{
    /**
     * @var callable
     */
    private $next;

    /**
     * @var
     */
    private $rows;

    function let(Rows $rows, mergenext $mergenext)
    {
        $this->rows = $rows;
        $this->rows->getRowsAssoc()->willReturn([]);
        $this->rows->getColumnHeaders()->willReturn([]);
        $this->next = $mergenext;
        $this->beConstructedWith('key');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(\ErgonTech\Tabular\MergeStep::class);
    }

    function it_is_a_step()
    {
        $this->shouldImplement(Step::class);
    }

    function it_calls_next_step()
    {
        $this->next->__invoke(Argument::type(Rows::class))->shouldBeCalled();
        $this->__invoke($this->rows, $this->next);
    }

    function it_returns_the_return_of_next()
    {
        $this->next->__invoke(Argument::type(Rows::class))->willReturn('hello');
        $this->__invoke($this->rows, $this->next)->shouldReturn('hello');
    }

    function it_merges_rows_based_on_comparison_of_a_key()
    {
        $this->beConstructedWith('key');

        $this->rows->getRowsAssoc()->willReturn([
            ['key' => 'val1', 'other' => 'one'],
            ['key' => 'val1', 'other' => 'two'],
            ['key' => 'val2', 'other' => 'three'],
            ['key' => 'val3', 'other' => 'four'],
            ['key' => 'val2', 'other' => 'five'],
            ['key' => 'val1', 'other' => 'six']
        ]);

        $this->rows->getColumnHeaders()->willReturn(['key', 'other']);

        $this->next->__invoke(Argument::type(Rows::class))->will(function ($args) {
            return $args[0];
        });

        $expectation = [
            ['key' => 'val1', 'other' => ['one', 'two', 'six']],
            ['key' => 'val2', 'other' => ['three', 'five']],
            ['key' => 'val3', 'other' => ['four']]
        ];

        $output = $this->__invoke($this->rows, $this->next);

        $output->getRowsAssoc()->shouldEqual($expectation);
    }


}

class mergenext
{
    function __invoke($x) { return $x; }
}
