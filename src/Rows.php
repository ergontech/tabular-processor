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
    private $columnHeaders;

    public function __construct(array $columnHeaders, array $dataRows = [])
    {
        $this->columnHeaders = $columnHeaders;
        $this->dataRows = $dataRows;
    }

    public function getColumnHeaders()
    {
        return $this->columnHeaders;
    }

    public function getNextRow()
    {
        try {
            return current($this->dataRows);
        } catch (\Exception $e) {
            throw $e;
        } finally {
            next($this->dataRows);
        }
    }

    public function getNextRowAssoc()
    {
        try {
            $row = $this->getNextRow();

            return array_combine($this->getColumnHeaders(), $row);
        } catch (\Exception $e) {
            
        }
    }
}
