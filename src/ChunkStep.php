<?php

namespace ErgonTech\Tabular;

class ChunkStep implements Step
{
    private $chunkSize;

    public function __construct($chunkSize)
    {
        $this->chunkSize = $chunkSize;
    }

    /**
     * Accepts a Rows object and returns a rows object
     *
     * Call next multiple times with subsets of the row data
     * Good for ensuring the next step will not outrun memory availability
     *
     * @param \ErgonTech\Tabular\Rows $rows
     * @param callable $next
     * @return Rows
     * @throws StepExecutionException
     */
    public function __invoke(Rows $rows, callable $next)
    {
        $newRows = array_reduce(
            array_chunk($rows->getRows(), $this->chunkSize),
            function ($allRows, $rowChunk) use ($rows, $next) {
                return array_merge($allRows,
                    $next(new Rows($rows->getColumnHeaders(), $rowChunk))->getRowsAssoc());
            }, []);
        return new Rows(array_keys(current($newRows)), array_map('array_values', $newRows));
    }
}
