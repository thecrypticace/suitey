<?php

namespace TheCrypticAce\Suitey;

use InvalidArgumentException;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Foundation\Application;
use TheCrypticAce\Suitey\Environment\Environment;

class Suitey
{
    /**
     * The laravel application instance
     * A container used to resolve dependencies
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $app;

    /**
     * A list of all executable steps
     *
     * @var \Illuminate\Support\Collection
     */
    private $steps;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->steps = new Collection;
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
            Environment::loadUsing($this->app);

            return $this->pipeline()->send($io)->then(function ($io) {
                return 0;
            });
        });
    }

    private function loadFromConfig()
    {
        $steps = new Collection(
            $this->app["config"]->get("suitey.steps") ?? []
        );

        $steps = $steps->map(function ($step) {
            return $this->normalizeConfigStep($step);
        });

        $steps = $steps->map(function ($step) {
            return $this->createConfigStep($step);
        });

        return $steps;
    }

    private function normalizeConfigStep($step)
    {
        if (is_string($step)) {
            return ["class" => $step, "options" => []];
        }

        if (is_array($step)) {
            return array_replace(["class" => null, "options" => []], $step);
        }

        throw new InvalidArgumentException("Steps must be provided by a class name or array wth 'class' and 'options' keys.");
    }

    private function createConfigStep($step)
    {
        $class = $step["class"];
        $options = $step["options"];

        return $this->app->makeWith($class, [
            "options" => $options,
        ]);
    }

    private function pipeline()
    {
        $pipeline = new Pipeline($this->app);

        return $pipeline->through($this->pending()->all());
    }

    private function pending()
    {
        $steps = $this->loadFromConfig()->merge($this->steps);

        return $steps->map(function ($step, $index) use ($steps) {
            return new PendingStep($step, 1 + $index, $steps->count());
        });
    }
}
