<?php

namespace TheCrypticAce\Suitey\Steps;

use Closure;
use TheCrypticAce\Suitey\IO;
use TheCrypticAce\Suitey\Process;

class Migrate implements Step
{
    public function __construct($database = null, $path = null)
    {
        $this->path = $path;
        $this->database = $database;
    }

    public function name()
    {
        return $this->database
            ? "Migrate {$this->database}"
            : "Migrate database";
    }

    public function handle(IO $io, Closure $next)
    {
        Process::artisan("migrate")->quiet()->run([
            "--path={$this->path}",
            "--database={$this->database}",
        ]);

        $exitCode = $next($io);

        Process::artisan("migrate:rollback")->quiet()->run([
            "--path={$this->path}",
            "--database={$this->database}",
        ]);

        return $exitCode;
    }
}
