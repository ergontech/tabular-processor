<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\GoogleSheetsLoadStep;
use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GoogleSheetsLoadStepSpec extends ObjectBehavior
{
    private $serviceSheets;
    private $spreadSheetsValues;
    private $sheetId;
    private $headerRangeName;
    private $dataRangeName;

    public function let(
        \Google_Service_Sheets $serviceSheets,
        \Google_Service_Sheets_Resource_SpreadsheetsValues $spreadsheetsValues,
        \Google_Service_Sheets_ValueRange $headerRange,
        \Google_Service_Sheets_ValueRange $dataRange
    ) {
        /** @var \PhpSpec\Wrapper\Collaborator|\Google_Service_Sheets_ValueRange $headerRange */
        $this->sheetId = '123';
        $this->headerRangeName = 'header';
        $this->dataRangeName = 'data';
        $this->spreadSheetsValues = $spreadsheetsValues;
        $this->serviceSheets = $serviceSheets;

        $headerRange->getValues()->willReturn([
            ['foo', 'bar']
        ]);

        $dataRange->getValues()->willReturn([
            ['valuefoo1', 'valuebar1'],
            ['valuefoo2', 'valuebar2']
        ]);

        $this->spreadSheetsValues
            ->get(
                $this->sheetId,
                $this->headerRangeName)
            ->willReturn($headerRange);

        $this->spreadSheetsValues
            ->get(
                $this->sheetId,
                $this->dataRangeName)
            ->willReturn($dataRange);

        $this->serviceSheets->spreadsheets_values = $spreadsheetsValues;

        $this->beConstructedWith($serviceSheets, $this->sheetId, $this->headerRangeName, $this->dataRangeName);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GoogleSheetsLoadStep::class);
    }

    public function it_is_a_step()
    {
        $this->shouldHaveType(Step::class);
    }

    public function it_is_invokable_and_returns_rows(Rows $rows)
    {
        $this->spreadSheetsValues
            ->get(Argument::type('string'), Argument::type('string'))
            ->shouldBeCalledTimes(2);
        $this->__invoke($rows)->shouldReturnAnInstanceOf(Rows::class);
    }

    public function it_can_return_rows_with_numeric_keys(Rows $rows)
    {
        /** @var Rows $rows */
        $rows = $this->__invoke($rows);
        $rows->getNextRow()->shouldEqual(['valuefoo1', 'valuebar1']);
    }

    public function it_can_return_rows_with_associative_keys(Rows $rows)
    {
        /** @var Rows $rows */
        $rows = $this->__invoke($rows);
        $rows->getNextRowAssoc()->shouldEqual(['foo' => 'valuefoo1', 'bar' => 'valuebar1']);
    }
}
