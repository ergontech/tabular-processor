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

    public function it_can_return_a_row()
    {
        $headers = ['foo', 'bar'];
        $dataRow = ['asdf', 'fdas'];
        $this->beConstructedWith($headers, [$dataRow]);

        $this->getNextRow()->shouldReturn($dataRow);
    }

    public function it_can_return_an_associative_row()
    {
        $headers = ['foo', 'bar'];
        $dataRow = ['asdf', 'fdas'];
        $this->beConstructedWith($headers, [$dataRow]);

        $this->getNextRowAssoc()->shouldReturn(['foo' => 'asdf', 'bar' => 'fdas']);
    }
}
