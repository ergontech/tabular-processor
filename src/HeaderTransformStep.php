<?php
/**
 * Created by IntelliJ IDEA.
 * User: matthewdev
 * Date: 16/27/7
 * Time: 14:37
 */

namespace ErgonTech\Tabular;


class HeaderTransformStep implements Step
{
    /**
     * @var callable
     */
    private $transformer;

    /**
     * HeaderTransformStep constructor.
     * @param callable $transformer
     */
    public function __construct(callable $transformer)
    {
        $this->transformer = $transformer;
    }


    public function transformColumnHeader($columnHeader)
    {
        return call_user_func($this->transformer, $columnHeader);
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
        /** @var array $oldHeaders */
        $oldHeaders = $rows->getColumnHeaders();

        $newHeaders = array_map([$this, 'transformColumnHeader'], $oldHeaders);
        return $next(new Rows($newHeaders, $rows->getRows()));
    }
}
