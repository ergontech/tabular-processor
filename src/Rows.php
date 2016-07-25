<?php
/**
 * Created by IntelliJ IDEA.
 * User: matthewdev
 * Date: 16/22/7
 * Time: 11:26
 */

namespace ErgonTech\Tabular;


class Rows
{
    /**
     * @var array
     */
    private $columnHeaders;

    /**
     * @var array
     */
    private $dataRows;

    /**
     * Rows constructor.
     * @param array $columnHeaders
     * @param array $dataRows
     */
    public function __construct(array $columnHeaders, array $dataRows = [])
    {
        $this->columnHeaders = $columnHeaders;
        $this->dataRows = $dataRows;
    }

    /**
     * @return array
     */
    public function getColumnHeaders()
    {
        return $this->columnHeaders;
    }

    /**
     * @return array|false
     */
    public function getNextRow()
    {
        /** @var array|false $value */
        $value = current($this->dataRows);
        next($this->dataRows);
        return $value;
    }

    /**
     * @return array|false
     */
    public function getNextRowAssoc()
    {
        $row = $this->getNextRow();
        return $row === false ? $row : array_combine($this->getColumnHeaders(), $row);
    }
}
