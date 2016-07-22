<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\GoogleSheetsLoadStep;
use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GoogleSheetsLoadStepSpec extends ObjectBehavior
{
    function let(\Google_Client $client, \Google_Service_Sheets $sheets)
    {


    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GoogleSheetsLoadStep::class);
    }

    function it_is_a_step()
    {
        $this->shouldHaveType(Step::class);
    }

    function it_is_invokable_and_returns_rows(Rows $rows)
    {
        $this->__invoke($rows)->shouldReturnAnInstanceOf(Rows::class);
    }
}
