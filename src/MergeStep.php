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
     * @var callable
     */
    private $mergeFunc;

    /**
     * @param callable $mergeFunc
     */
    public function __construct(callable $mergeFunc)
    {
        $this->mergeFunc = $mergeFunc;
    }

    /**
     * @param \ErgonTech\Tabular\Rows $rows
     * @param callable $next
     * @return Rows
     * @throws StepExecutionException
     */
    public function __invoke(Rows $rows, callable $next)
    {
        $newRowsByKey = array_reduce($rows->getRowsAssoc(), $this->mergeFunc, []);

        return $next(new Rows(array_keys(current($newRowsByKey) ?: []), array_values($newRowsByKey)));
    }
}
