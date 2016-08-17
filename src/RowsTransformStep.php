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
        $columnHeaders = $rows->getColumnHeaders();

        $transformedRows = array_reduce($rows->getRows(), function ($carry, $row) use ($columnHeaders) {
            $originalAssocRow = array_combine($columnHeaders, $row);
            $transformedAssocRow = call_user_func($this->transformer, $originalAssocRow);

            // Return JUST THE VALUES of the transformed associative row
            return array_merge($carry, [array_values($transformedAssocRow)]);
        }, []);

        return new Rows($columnHeaders, $transformedRows);
    }
}
