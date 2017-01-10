<?php

namespace ErgonTech\Tabular;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Logs data about the next step to run
 * @package ErgonTech\Tabular
 * @author Matthew Wells <matthew@ergon.tech>
 */
class LoggingStep implements Step
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LoggingStep constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
        $nextInfo = $this->getNextInfo($next);
        try {
            $this->logger->log(
                LogLevel::INFO,
                sprintf('%s started. %d rows to process', $nextInfo, count($rows->getRows())));
            $result = $next($rows);
        } catch (StepExecutionException $e) {
            $this->logger->log(
                LogLevel::ERROR,
                sprintf('Exception during %s: "%s"', $nextInfo, $e->getMessage()));
            throw $e;
        }

        $this->logger->log(
            LogLevel::DEBUG,
            sprintf('%s finished. %d rows in output', $nextInfo, count($result->getRows())));

        return $result;
    }

    /**
     * Provide info about "next"
     * @param callable $next
     * @return string
     */
    private function getNextInfo(callable $next)
    {
        if ($next instanceof Step) {
            return get_class($next);
        }

        // It'd be great to introduce a doc comment evaluator here
        // Then we could introduce an annotation: @description
        if ($next instanceof \Closure) {
            $info = new \ReflectionFunction($next);
            return (new \ReflectionClass($info->getClosureThis()))->getShortName();
        }

        if (is_string($next)) {
            return $next;
        }

        return 'step';
    }
}
