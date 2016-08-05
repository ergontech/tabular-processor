<?php

namespace ErgonTech\Tabular;


class Processor
{
    /**
     * @var \SplStack
     */
    private $steps;

    /**
     * @var bool
     */
    private $stepsLocked = false;

    /**
     * Processor constructor.
     */
    public function __construct()
    {
        $this->steps = new \SplStack();
        $this->steps->setIteratorMode(\SplStack::IT_MODE_LIFO | \SplStack::IT_MODE_KEEP);
        $this->steps[] = $this;
    }

    /**
     * @return Rows
     */
    public function run()
    {
        $step = $this->steps->top();
        return  $step(new Rows([]));
    }

    /**
     * Accepts a Rows object and returns a rows object
     *
     * @param \ErgonTech\Tabular\Rows $rows
     * @return Rows
     */
    public function __invoke(Rows $rows)
    {
        return $rows;
    }

    /**
     * @param Step $step
     * @return void
     */
    public function addStep(Step $step)
    {
        $next = $this->steps->top();

        $callback = function (Rows $rows) use ($next) {
            return $this($rows, $next);
        };

        $this->steps[] = $callback->bindTo($step);
    }}
