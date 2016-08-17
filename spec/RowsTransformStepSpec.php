<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RowsTransformStepSpec extends ObjectBehavior
{
    private $rows;

    function let(Rows $rows)
    {
        $f = function ($x) { return $x; };
        $this->beConstructedWith($f);

        $this->rows = $rows;
        $this->rows->getColumnHeaders()
            ->willReturn(['foo', 'bar']);

        $rows->getRows()
            ->willReturn([
                ['a1', 'a2'],
                ['b1', 'b2']
            ]);

        $rows->getRowsAssoc()
            ->willReturn([
                [
                    'foo' => 'a1',
                    'bar' => 'a2'
                ],
                [
                    'foo' => 'b1',
                    'bar' => 'b2'
                ]
            ]);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(\ErgonTech\Tabular\RowsTransformStep::class);
    }

    function it_needs_a_callable_during_construct()
    {
        $this->shouldThrow()->during('__construct', [null]);
    }

    function it_is_a_step()
    {
        $this->shouldImplement(Step::class);
    }

    function it_returns_rows_upon_invocation()
    {
        $this->__invoke($this->rows, function ($x) { return $x; })->shouldReturnAnInstanceOf(Rows::class);
    }

    function it_transforms_rows_upon_invocation()
    {
        $transformerFunc = function (array $row) {
            return [
                'bar' => [
                    'foo' => $row['foo'],
                    'test' => $row['bar']
                ]
            ];
        };
        $this->beConstructedWith($transformerFunc);

        $returnedRows = $this->__invoke($this->rows, function ($x) { return $x; });

        $returnedRows->shouldBeAnInstanceOf(Rows::class);
        $returnedRows->getRows()->shouldReturn([
            [['foo' => 'a1', 'test' => 'a2']],
            [['foo' => 'b1', 'test' => 'b2']]
        ]);
        $returnedRows->getColumnHeaders()->shouldReturn(['bar']);

    }
}
