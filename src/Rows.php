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
     * @var array
     */
    private $rowsAssoc;

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
     * @return array
     */
    public function getRows()
    {
        return $this->dataRows;
    }

    /**
     * @return array
     */
    public function getRowsAssoc()
    {
        if (is_null($this->rowsAssoc)) {
            $this->rowsAssoc = array_map(function ($dataRow) {
                return array_combine($this->getColumnHeaders(), $dataRow);
            }, $this->getRows());
        }

        return $this->rowsAssoc;
    }
}
