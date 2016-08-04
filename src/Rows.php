<?php
/**
 * Created by IntelliJ IDEA.
 * User: matthewdev
 * Date: 16/22/7
 * Time: 11:26
 */

namespace ErgonTech\Tabular;


use Symfony\Component\Process\Exception\LogicException;

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
     * @throws \LogicException
     */
    public function getRowsAssoc()
    {
        if (is_null($this->rowsAssoc)) {
            $headers = $this->getColumnHeaders();
            $this->rowsAssoc = array_map(function ($dataRow) use($headers) {
                // If there is an empty row value for a given column, fill it in with null
                if (count($headers) > count($dataRow)) {
                    return array_combine(
                        $headers,
                        array_merge(
                            $dataRow,
                            array_fill(count($dataRow), count($headers) - count($dataRow), null)));
                }

                if (count($headers) < count($dataRow)) {
                    throw new \LogicException('The length of data must be at most equal to that of the headers!');
                }

                return array_combine($headers, $dataRow);
            }, $this->getRows());
        }

        return $this->rowsAssoc;
    }
}
