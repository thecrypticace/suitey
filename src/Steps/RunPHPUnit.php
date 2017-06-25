<?php

namespace TheCrypticAce\Suitey\Steps;

use Closure;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Process;

class RunPHPUnit implements Step
{
    public function name()
    {
        return "Run PHPUnit";
    }

    public function handle(IO $io, Closure $next)
    {
        $process = Process::binary("vendor/phpunit/phpunit/phpunit");
        $process->useTty(! $this->isBeingTested());
        $process->run($io->argv()->slice(2)->options()->all());

        return $next($io);
    }

    private function isBeingTested()
    {
        return defined("RUNNING_SUITEY_TESTS");
    }
}
