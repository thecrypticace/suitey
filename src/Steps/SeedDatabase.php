<?php

namespace TheCrypticAce\Suitey\Steps;

use Closure;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Process;

class SeedDatabase implements Step
{
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function name()
    {
        return "Seed database using {$this->class}";
    }

    public function handle(IO $io, Closure $next)
    {
        Process::artisan("db:seed")->quiet()->run([
            "--class={$this->class}"
        ]);

        return $next($io);
    }
}
