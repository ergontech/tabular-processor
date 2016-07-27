<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use ErgonTech\Tabular\TransformStep;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransformStepSpec extends ObjectBehavior
{
    private $rowsReturner;
    private $outHeaders;
    private $rows;

    function let(Rows $rows)
    {
        $mapping = ['a' => 'b', 'c' => 'd'];
        $inHeaders = ['a', 'c'];
        $this->outHeaders = ['b', 'd'];

        $this->rowsReturner = function ($rows) { return $rows; };
        $rows->getColumnHeaders()->willReturn($inHeaders);
        $rows->getRows()->willReturn([]);
        $this->beConstructedWith($mapping);
        $this->rows = $rows;
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(TransformStep::class);
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

    function it_returns_a_mapping_for_a_mapped_column_header()
    {
        $oldColumnHeader = 'a';
        $newColumnHeader = 'b';
        $this->getMappedColumnHeader($oldColumnHeader)->shouldReturn($newColumnHeader);
    }

    function it_returns_the_same_value_for_column_header_when_not_mapped()
    {
        $columnHeader = 'notmapped';
        $this->getMappedColumnHeader($columnHeader)->shouldReturn($columnHeader);
    }

}
