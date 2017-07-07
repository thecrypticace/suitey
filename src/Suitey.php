<?php

namespace TheCrypticAce\Suitey;

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
     * @var \TheCrypticAce\Suitey\Steps
     */
    private $steps;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->steps = new Steps;
    }

    public function add($steps)
    {
        $this->steps = $this->steps->merge(array_wrap($steps));
    }

    public function fresh()
    {
        return tap(clone $this, function ($instance) {
            $instance->steps = new Steps;
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

    private function steps()
    {
        return $this->stepsFromConfig()->merge($this->steps);
    }

    private function stepsFromConfig()
    {
        return new Steps(
            $this->app["config"]->get("suitey.steps") ?? []
        );
    }

    private function pipeline()
    {
        return $this->steps()->pipeline($this->app);
    }
}
