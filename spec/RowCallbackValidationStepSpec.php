<?php

namespace spec\ErgonTech\Tabular;

use ErgonTech\Tabular\RowCallbackValidationStep;
use ErgonTech\Tabular\Rows;
use ErgonTech\Tabular\RowValidationException;
use ErgonTech\Tabular\RowValidator;
use ErgonTech\Tabular\Step;
use ErgonTech\Tabular\StepExecutionException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RowCallbackValidationStepSpec extends ObjectBehavior
{
    private $validator;

    public function let(RowValidator $validator)
    {
        $anyLevel = Argument::type('int');
        $validator->__invoke(['bad' => 'bad!'], $anyLevel)->willReturn(RowValidator::ERROR);
        $validator->__invoke(['good' => 'great!'], $anyLevel)->willReturn(RowValidator::OK);
        $validator->__invoke(['fine' => 'info'], $anyLevel)->willReturn(RowValidator::INFO);
        $validator->__invoke(['hmm' => 'maybe...'], $anyLevel)->willReturn(RowValidator::WARNING);

        $this->validator = $validator;

        $this->beConstructedWith($this->validator, RowValidator::WARNING);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(RowCallbackValidationStep::class);
    }

    public function it_is_a_step()
    {
        $this->shouldHaveType(Step::class);
    }

    public function it_is_invokable_and_returns_rows(Rows $rows)
    {
        $this->__invoke($rows)->shouldReturnAnInstanceOf(Rows::class);
    }

    public function it_validates_each_row(Rows $rows)
    {
        $returnRows = [
            ['good' => 'great!'],
            ['good' => 'great!'],
            ['good' => 'great!']
        ];

        $rows->getNextRowAssoc()->will(function() use(&$returnRows) {
            $ret = array_shift($returnRows);
            if (is_null($ret)) {
                return false;
            }
            return $ret;
        });
        $this->validator->__invoke(['good' => 'great!'], RowValidator::WARNING)->shouldBeCalledTimes(3);
        $this->__invoke($rows);
    }

    public function it_stops_validation_upon_failure(Rows $rows)
    {
        $returnRows = [
            ['good' => 'great!'],
            ['bad' => 'bad!'],
            ['hmm' => 'maybe...']
        ];

        $rows->getNextRowAssoc()->will(function() use(&$returnRows) {
            $ret = array_shift($returnRows);
            if (is_null($ret)) {
                return false;
            }
            return $ret;
        });

        $this->validator->__invoke(['good' => 'great!'], RowValidator::WARNING)->shouldBeCalled();
        $this->validator->__invoke(['bad' => 'bad!'], RowValidator::WARNING)
            ->willThrow(RowValidationException::class)
            ->shouldBeCalled();
        $this->validator->__invoke(['hmm' => 'maybe...'], RowValidator::WARNING)->shouldNotBeCalled();

        $this->shouldThrow(StepExecutionException::class)->during('__invoke', [$rows]);
        $this->__invoke($rows);
    }
}
