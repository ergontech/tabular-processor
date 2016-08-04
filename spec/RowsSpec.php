<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\Rows;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RowsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(Rows::class);
    }

    public function it_can_return_its_own_headers()
    {
        $headers = ['foo', 'bar'];
        $this->beConstructedWith($headers, []);
        $this->getColumnHeaders()->shouldReturn($headers);
    }

    public function it_can_get_rows()
    {
        $headers = ['foo', 'bar'];
        $dataRows = [['asdf', 'fdas']];

        $this->beConstructedWith($headers, $dataRows);
        $this->getRows()->shouldReturn($dataRows);
    }

    public function it_can_get_rows_associatively()
    {
        $headers = ['foo', 'bar'];
        $dataRows = [['asdf', 'fdas']];
        $resultRowsAssoc = [];
        foreach ($dataRows as $dataRow) {
            $resultRowsAssoc[] = array_combine($headers, $dataRow);
        }

        $this->beConstructedWith($headers, $dataRows);
        $this->getRowsAssoc()->shouldReturn($resultRowsAssoc);

    }

    public function it_returns_nulls_for_empty_row_values_accessed_associatively()
    {
        $headers = ['foo', 'bar', 'baz'];
        $dataRows = [['asdf', 'fdsa']];
        $resultRowsAssoc = [
            [
                'foo' => 'asdf',
                'bar' => 'fdsa',
                'baz' => null
            ]
        ];

        $this->beConstructedWith($headers, $dataRows);
        $this->getRowsAssoc()->shouldBeLike($resultRowsAssoc);
    }
}
