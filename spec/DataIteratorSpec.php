<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\DataProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DataProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DataProvider::class);
    }

    function it_implements_DataProvider_interface()
    {
        $this->shouldHaveType(\Iterator::class);
    }

    function it_must_be_provided_a_DataSource_upon_initialization()
    {
        $this->beConstructedWith(DataSource::class);
    }

    function it_reads_a_row_from_the_DataSource_when_next_is_called(DataSource $dataSource)
    {
        $dataSource->getNextRow()->willReturn(['foo' => 'fah', 'bar' => 'bah']);
        $this->beConstructedWith($dataSource);

    }
}
