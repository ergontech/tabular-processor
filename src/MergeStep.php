<?php

namespace ErgonTech\Tabular;

/**
 * Class MergeStep
 * Given a key and merge function
 * @package ErgonTech\Tabular
 */
class MergeStep implements Step
{
    /**
     * @var mixed
     */
    private $primaryKey;

    /**
     * @param $primaryKey
     */
    public function __construct($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    /**
     * @param \ErgonTech\Tabular\Rows $rows
     * @param callable $next
     * @return Rows
     * @throws StepExecutionException
     */
    public function __invoke(Rows $rows, callable $next)
    {
        $primaryKeys = array_unique(array_column($rows->getRowsAssoc(), $this->primaryKey));

        // A reformatted, associative array of row values (without their primary key), using the PK as hash
        $keyedByPrimary = array_reduce($primaryKeys, function ($keyedByPrimary, $key) use ($rows) {
            // These are used for the internal assoc array
            $nonKeyColumns = array_diff($rows->getColumnHeaders(), [$this->primaryKey]);

            // From all the rows, these match the given key
            $pkRows = array_filter($rows->getRowsAssoc(), function ($row) use ($key) {
                return $row[$this->primaryKey] === $key;
            });

            // Fill the values for each PK-keyed index
            $keyedByPrimary[$key] = array_reduce($nonKeyColumns, function ($innerPkValues, $column) use ($pkRows) {
                $innerPkValues[$column] = array_column($pkRows, $column);
                return $innerPkValues;
            }, []);
            return $keyedByPrimary;
        }, []);

        // Rebuild a row-style array, starting by iteration over each found item
        $dataRows = array_map(function ($primaryColumnKey) use ($rows, $keyedByPrimary) {
            // Map the column headers, building columns/values for each row
            return array_map(function ($columnHeader) use ($primaryColumnKey, $keyedByPrimary) {
                return $columnHeader === $this->primaryKey
                    ? $primaryColumnKey
                    : $keyedByPrimary[$primaryColumnKey][$columnHeader];
            }, $rows->getColumnHeaders());
        }, array_keys($keyedByPrimary));
        return $next(new Rows($rows->getColumnHeaders(), $dataRows));
    }
}
