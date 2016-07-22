<?php
/**
 * Created by IntelliJ IDEA.
 * User: matthewdev
 * Date: 16/22/7
 * Time: 11:26
 */

namespace ErgonTech\Tabular;


class Steps
{
    /**
     * @var \SplQueue
     */
    private $queue;

    public function __construct()
    {
        $this->queue = new \SplQueue();
    }

    /**
     * @param callable $step
     */
    public function add(Step $step)
    {
        $this->queue->enqueue($step);
    }

    /**
     * @return Step|null
     */
    public function getNext()
    {
        if ($this->queue->count()) {
            return $this->queue->dequeue();
        }

        return null;
    }
}
