<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use ErgonTech\Tabular\HeaderTransformStep;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HeaderTransformStepSpec extends ObjectBehavior
{
    private $rowsReturner;
    private $outHeaders;
    private $rows;

    function let(Rows $rows)
    {
        $inHeaders = ['a', 'b'];
        $this->outHeaders = ['A', 'B'];

        $this->rowsReturner = function ($rows) { return $rows; };
        $rows->getColumnHeaders()->willReturn($inHeaders);
        $rows->getRows()->willReturn([]);
        $this->beConstructedWith(function($columnHeader) {
            return strtoupper($columnHeader);
        });
        $this->rows = $rows;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HeaderTransformStep::class);
    }

    function it_needs_a_callable_during_init()
    {
        $this->shouldThrow()->during('__construct', [null]);
    }

    function it_is_a_step()
    {
        $this->shouldHaveType(Step::class);
    }
    function it_returns_rows_upon_invocation(Rows $rows)
    {
        $this->__invoke($rows, $this->rowsReturner)->shouldReturnAnInstanceOf(Rows::class);
    }

    function it_transforms_headers_given_a_step_mapping()
    {
        /** @var Rows $outRows */
        $outRows = $this->__invoke($this->rows, $this->rowsReturner);
        $outRows->getColumnHeaders()->shouldReturn($this->outHeaders);
    }

    function it_returns_a_transformed_column_header()
    {
        $oldColumnHeader = 'a';
        $newColumnHeader = 'A';
        $this->transformColumnHeader($oldColumnHeader)->shouldReturn($newColumnHeader);
    }
}
