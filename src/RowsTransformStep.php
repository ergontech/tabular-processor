<?php

namespace ErgonTech\Tabular;

class RowsTransformStep implements Step
{
    /**
     * @var callable
     */
    private $transformer;

    /**
     * @param callable $transformer
     */
    public function __construct(callable $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Accepts a Rows object and returns a rows object
     *
     * Pass each row to transformer function (associatively!)
     *
     * Return the resulting new rowset
     *
     * @param \ErgonTech\Tabular\Rows $rows
     * @param callable $next
     * @return Rows
     * @throws StepExecutionException
     */
    public function __invoke(Rows $rows, callable $next)
    {
        $transformedRowsAssoc = array_map($this->transformer, $rows->getRowsAssoc());

        $transformedColumnHeaders = array_keys(current($transformedRowsAssoc));
        $transformedRows = array_map('array_values', $transformedRowsAssoc);

        return $next(new Rows($transformedColumnHeaders, $transformedRows));
    }
}
