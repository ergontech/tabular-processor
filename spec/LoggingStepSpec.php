<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\LoggingStep;
use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\Step;
use ErgonTech\Tabular\StepExecutionException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LoggingStepSpec extends ObjectBehavior
{
    private $logger;
    private $rowsReturner;

    function let(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->rowsReturner = function ($rows) {
            return $rows;
        };

        $this->beConstructedWith($logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LoggingStep::class);
    }

    function it_is_a_step()
    {
        $this->shouldHaveType(Step::class);
    }

    function it_requires_a_logger()
    {
        $this->beConstructedWith(LoggerInterface::class);
    }

    function it_logs_the_output_of_the_next_step(Rows $rows)
    {
        $this->logger->log(LogLevel::INFO, Argument::type('string'))->shouldBeCalledTimes(2);
        $this->logger->log(LogLevel::ERROR, Argument::type('string'))->shouldNotBeCalled();
        $this->__invoke($rows, $this->rowsReturner);
    }

    function it_logs_an_error_when_a_step_has_a_problem(Rows $rows)
    {
        $msg = 'There was a step error!';
        $f = function () use ($msg) {
            throw new StepExecutionException($msg);
        };
        $this->shouldThrow(StepExecutionException::class)->during('__invoke', [$rows, $f]);
        $this->logger->log(LogLevel::ERROR, "Exception during step: \"{$msg}\"")->shouldHaveBeenCalled();
    }

    function it_returns_rows_upon_invocation(Rows $rows)
    {
        $this->__invoke($rows, $this->rowsReturner)->shouldReturnAnInstanceOf(Rows::class);
    }
}
