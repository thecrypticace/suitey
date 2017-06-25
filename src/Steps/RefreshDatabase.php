<?php

namespace TheCrypticAce\Suitey\Steps;

use Closure;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Process;

class RefreshDatabase implements Step
{
    public function __construct($database = null, $path = null)
    {
        $this->path = $path;
        $this->database = $database;
    }

    public function name()
    {
        return "Refresh db {$this->database}";
    }

    public function handle(IO $io, Closure $next)
    {
        Process::artisan("migrate:refresh")->quiet()->run([
            "--path={$this->path}",
            "--database={$this->database}",
        ]);

        return $next($io);
    }
}
