<?php

namespace ErgonTech\Tabular;

class IteratorStep implements Step
{
    /**
     * @var array
     */
    private $items;

    /**
     * @var string
     */
    private $columnHeader;

    public function __construct(array $items, $columnHeader)
    {
        $this->items = $items;
        $this->columnHeader = $columnHeader;
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
        $columnHeaders = array_unique(array_merge($rows->getColumnHeaders(), [$this->columnHeader]));

        if (count($columnHeaders) !== count($rows->getColumnHeaders()) + 1) {
            throw new StepExecutionException('Column headers must be unique!');
        }

        $rows = array_reduce($this->items, function ($carry, $item) use ($next, $rows, $columnHeaders) {
            $rowMapper = function ($rows) use($item) {
                return array_map(function ($row) use ($item) { return array_merge($row, [$item]); }, $rows);
            };
            /** @var Rows $retVal */
            $retVal = $next(new Rows($columnHeaders, $rowMapper($rows->getRows())));
            return array_merge($carry, $retVal->getRows());
        }, []);

        return new Rows($columnHeaders, $rows);
    }
}
