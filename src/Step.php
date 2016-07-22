<?php

namespace ErgonTech\Tabular;


use ErgonTech\Tabular\Rows;

interface Step
{
    /**
     * Accepts a Rows object and returns a rows object
     *
     * @param \ErgonTech\Tabular\Rows $rows
     * @return Rows
     * @throws StepExecutionException
     */
    public function __invoke(Rows $rows);
}
