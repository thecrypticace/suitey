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
        $arguments = [];

        if ($this->path) {
            $arguments[] = "--path={$this->path}";
        }

        if ($this->database) {
            $arguments[] = "--database={$this->database}";
        }

        Process::artisan("migrate")->quiet()->run($arguments);

        $exitCode = $next($io);

        Process::artisan("migrate:rollback")->quiet()->run($arguments);

        return $exitCode;
    }
}
