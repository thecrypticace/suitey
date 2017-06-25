<?php

namespace TheCrypticAce\Suitey;

use Closure;
use TheCrypticAce\Suitey\Steps\Step;

class PendingStep implements Step
{
    public function __construct(Step $step, $number, $total)
    {
        $this->step = $step;
        $this->total = $total;
        $this->number = $number;
    }

    public function name()
    {
        return $this->step->name();
    }

    public function handle(IO $io, Closure $next)
    {
        $io->output()->writeln(
            "<fg=blue>[{$this->number}/{$this->total}]</> <fg=yellow>{$this->name()}</>"
        );

        return $this->step->handle($io, $next);
    }
}
