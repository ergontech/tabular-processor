<?php

namespace ErgonTech\Tabular;

interface RowValidator
{
    /**
     * There was no issue
     */
    const OK = 0;

    /**
     * No issues, but information was generated that may be useful
     */
    const INFO = 1;

    /**
     * A recoverable issue occured, but it should probably be resolved
     */
    const WARNING = 2;

    /**
     * A fatal issue occurred and the system could not get around it
     */
    const ERROR = 3;

    /**
     * Accepts a row in associative form and returns
     * @param array $row
     * @param int $maxIssueLevel
     * @return void
     * @throws RowValidationException
     */
    public function __invoke(array $row, $maxIssueLevel = self::WARNING);
}
