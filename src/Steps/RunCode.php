<?php

namespace TheCrypticAce\Suitey\Steps;

use Closure;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Process;

class RunCode implements Step
{
    private $name;
    private $code;

    public function __construct($name, Closure $code = null)
    {
        $this->name = $name;
        $this->code = $code;
        $this->hasRun = false;
    }

    public function name()
    {
        return value($this->name);
    }

    public function handle(IO $io, Closure $next)
    {
        $this->hasRun = true;

        return ($this->code)($io, $next) ?? $next($io);
    }
}
