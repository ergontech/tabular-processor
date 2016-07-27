<?php

namespace ErgonTech\Tabular;

class TransformStep implements Step
{
    /**
     * @var array
     */
    private $mapping;

    /**
     * TransformStep constructor.
     * @param array $mapping
     */
    public function __construct(array $mapping)
    {
        $this->mapping = $mapping;
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
        /** @var array $oldHeaders */
        $oldHeaders = $rows->getColumnHeaders();

        $newHeaders = array_map(function ($headerColumn) {
            return array_key_exists($headerColumn, $this->mapping)
                ? $this->mapping[$headerColumn] : $headerColumn;
        }, $oldHeaders);
        return new Rows($newHeaders, $rows->getRows());
    }
}
