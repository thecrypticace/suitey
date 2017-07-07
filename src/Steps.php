<?php

namespace TheCrypticAce\Suitey;

use InvalidArgumentException;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Foundation\Application;

class Steps extends Collection
{
    public function normalize()
    {
        return $this->map(function ($step, $index) {
            return $this->normalizeStep($step, $index);
        });
    }

    private function normalizeStep($step, $index)
    {
        if (is_string($step)) {
            return ["class" => $step, "options" => []];
        }

        if (is_array($step)) {
            return array_replace(["class" => null, "options" => []], $step);
        }

        if (is_object($step)) {
            return $step;
        }

        throw new InvalidArgumentException(vsprintf("%s %s", [
            "Invalid step configured at index {$index} in [config/suitey.php].",
            "Valid steps are class names or arrays wth 'class' and 'options' keys.",
        ]));
    }

    public function createUsing(Application $app)
    {
        return $this->map(function ($step) use ($app) {
            return $this->createStepUsing($step, $app);
        });
    }

    private function createStepUsing($step, Application $app)
    {
        return is_object($step) ? $step : $app->makeWith($step["class"], [
            "options" => $step["options"]
        ]);
    }

    public function toPending()
    {
        return $this->map(function ($step, $index) {
            return new PendingStep($step, 1 + $index, $this->count());
        });
    }

    public function pipeline(Application $app)
    {
        return (new Pipeline($app))->through(
            $this->normalize()->createUsing($app)->toPending()->all()
        );
    }
}
