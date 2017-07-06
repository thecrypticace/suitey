<?php

namespace TheCrypticAce\Suitey;

use InvalidArgumentException;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Foundation\Application;

class ConfigSteps
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public static function new(Application $app)
    {
        return new static($app);
    }

    public function load()
    {
        return $this->config()->map(function ($step, $index) {
            return $this->create($this->normalize($step, $index));
        });
    }

    private function config()
    {
        return new Collection(
            $this->app["config"]->get("suitey.steps") ?? []
        );
    }

    private function normalize($step, $index)
    {
        if (is_string($step)) {
            return ["class" => $step, "options" => []];
        }

        if (is_array($step)) {
            return array_replace(["class" => null, "options" => []], $step);
        }

        throw new InvalidArgumentException(
            "Invalid configuration step at index {$index}. Steps must be specified by class name or array wth 'class' and 'options' keys."
        );
    }

    private function create($step)
    {
        return $this->app->makeWith($step["class"], [
            "options" => $step["options"],
        ]);
    }
}
