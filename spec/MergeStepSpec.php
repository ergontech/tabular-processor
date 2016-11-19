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
        $this->next = $mergenext;
        $this->beConstructedWith(function ($current, $new) {
            return array_merge_recursive($current, $new);
        });
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
        $this->beConstructedWith(function ($current, $new) {
            $keyVal = $new['key'];
            if (array_key_exists($keyVal, $current)) {
                $current[$keyVal] = [
                    'key' => $keyVal,
                    'other' => array_merge($current[$keyVal]['other'], $new['other'])
                ];
            } else {
                $current[$keyVal] = $new;
            }
            return $current;
        });

        $this->rows->getRowsAssoc()->willReturn([
            ['key' => 'val1', 'other' => ['one' => 'asdf']],
            ['key' => 'val1', 'other' => ['two' => 'fdsa']],
            ['key' => 'val2', 'other' => ['one']],
            ['key' => 'val3', 'other' => ['one']],
            ['key' => 'val2', 'other' => ['two']]
        ]);

        $this->next->__invoke(Argument::type(Rows::class))->will(function ($args) {
            return $args[0];
        });

        $expectation = [
            ['key' => 'val1', 'other' => ['one' => 'asdf', 'two' => 'fdsa']],
            ['key' => 'val2', 'other' => ['one', 'two']],
            ['key' => 'val3', 'other' => ['one']]
        ];

        $output = $this->__invoke($this->rows, $this->next);

        $output->getRowsAssoc()->shouldEqual($expectation);
    }


}

class mergenext
{
    function __invoke($x) { return $x; }
}
