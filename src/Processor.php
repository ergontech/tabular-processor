<?php

namespace ErgonTech\Tabular;


class Processor
{
    /**
     * @var Steps
     */
    private $steps;

    /**
     * @var Rows
     */
    private $rows;

    /**
     * Processor constructor.
     * @param Rows $rows
     * @param Steps $steps
     */
    public function __construct(Rows $rows, Steps $steps)
    {
        $this->rows = $rows;
        $this->steps = $steps;
    }

    /**
     * @return Rows
     */
    public function run()
    {
        $rows = $this->rows;
        while(($step = $this->steps->getNext()) instanceof Step) {
            try {
                $rows = $step($rows);
            } catch (\Exception $e) {
                break;
            }
        }

        return $rows;
    }
}
