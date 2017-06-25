<?php

namespace TheCrypticAce\Suitey;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;

class Suitey
{
    /**
     * A list of all executable steps
     *
     * @var \Illuminate\Support\Collection
     */
    private $steps;

    /**
     * A container used to resolve dependencies
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->steps = new Collection;
        $this->container = $container;
    }

    public function add($steps)
    {
        $this->steps = $this->steps->merge(array_wrap($steps));
    }

    public function fresh()
    {
        return tap(clone $this, function ($instance) {
            $instance->steps = new Collection;
        });
    }

    public function run(IO $io)
    {
        return Process::usingOutput($io->output(), function () use ($io) {
            return $this->pipeline()->send($io)->then(function ($io) {
                return 0;
            });
        });
    }

    private function pipeline()
    {
        $pipeline = new Pipeline($this->container);

        return $pipeline->through($this->pending()->all());
    }

    private function pending()
    {
        return $this->steps->map(function ($step, $index) {
            return new PendingStep($step, 1 + $index, $this->steps->count());
        });
    }
}
