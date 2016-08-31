<?php

namespace ErgonTech\Tabular;

class GoogleSheetsLoadStep implements Step
{
    /**
     * @var \Google_Service_Sheets
     */
    private $sheetsService;

    /**
     * @var string
     */
    private $sheetId;

    /**
     * @var string
     */
    private $headerRangeName;

    /**
     * @var string
     */
    private $dataRangeName;

    /**
     * GoogleSheetsLoadStep constructor.
     * @param \Google_Service_Sheets $sheetsService
     * @param $sheetId
     * @param $headerRangeName
     * @param $dataRangeName
     */
    public function __construct(
        \Google_Service_Sheets $sheetsService,
        $sheetId,
        $headerRangeName,
        $dataRangeName
    ) {
        $this->sheetsService = $sheetsService;
        $this->sheetId = $sheetId;
        $this->headerRangeName = $headerRangeName;
        $this->dataRangeName = $dataRangeName;
    }

    /**
     * Accepts a Rows object and returns a rows object
     *
     * @param \ErgonTech\Tabular\Rows $rows
     * @param callable $next
     * @return Rows
     * @throws StepExecutionException
     */
    public function __invoke(Rows $rows, callable $next)
    {
        $headers = $this->sheetsService->spreadsheets_values->get($this->sheetId, $this->headerRangeName)->getValues();
        $values = $this->sheetsService->spreadsheets_values->get($this->sheetId, $this->dataRangeName)->getValues();
        // Google sends the *minimum* back. Meaning if there are "trailing NULL entries" those won't be included!
        // However, it's very important to our processor that column count matches the count of the columns in each row
        // Thus, we fill!
        $fill = array_fill(0, count($headers[0]), null);
        return $next(new Rows(
            $headers[0],
            array_map(function ($data) use($fill) {
                return $data + $fill; // For numeric arrays, the plus operator works like array_merge works for associative arrays. _However_ the order is reversed. /shrug
            }, $values)));
    }
}
