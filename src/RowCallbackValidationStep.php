<?php

namespace ErgonTech\Tabular;

class RowCallbackValidationStep implements Step
{
    /**
     * @var RowValidator
     */
    private $rowValidator;

    /**
     * @var int
     */
    private $maxValidationLevel;

    /**
     * RowCallbackValidationStep constructor.
     * @param RowValidator $rowValidator
     * @param int $maxValidationLevel
     */
    public function __construct(RowValidator $rowValidator, $maxValidationLevel = RowValidator::WARNING)
    {
        $this->rowValidator = $rowValidator;
        $this->maxValidationLevel = $maxValidationLevel;
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
        $newRows = clone $rows;
        try {
            foreach($newRows->getRowsAssoc() as $row) {
                $this->rowValidator->__invoke($row, $this->maxValidationLevel);
            }
        } catch (RowValidationException $e) {
            throw new StepExecutionException($e->getMessage());
        }

        return $next($newRows);
    }
}
